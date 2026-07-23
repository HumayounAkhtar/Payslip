<?php

namespace App\Http\Controllers;

use App\Models\ReceiptFieldMapping;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ReceiptController extends Controller
{
    /**
     * Show the receipt editor dashboard.
     */
    public function showEditor()
    {
        return view('receipt-editor');
    }

    /**
     * Generate and download the high-fidelity mock receipt PNG.
     */
    public function generateReceipt(Request $request)
    {
        // 1. Validate Form Inputs
        $validated = $request->validate([
            'device_time' => ['required', 'string', 'max:10'],
            'battery_status' => ['required', 'string', 'in:full,medium,low'],
            'signal_status' => ['required', 'string', 'in:4-bars,3-bars,2-bars,1-bar'],
            'net_amount' => ['required', 'string', 'max:30'],
            'net_asset' => ['required', 'string', 'max:15'],
            'network' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'txid' => ['required', 'string'],
            'amount' => ['required', 'string', 'max:30'],
            'amount_asset' => ['required', 'string', 'max:15'],
            'network_fee' => ['required', 'string', 'max:30'],
            'fee_asset' => ['required', 'string', 'max:15'],
            'withdrawal_wallet' => ['required', 'string', 'max:50'],
            'date' => ['required', 'string', 'max:40'],
        ]);

        // 2. Initialize Image Manager with GD driver
        $manager = new ImageManager(new Driver());

        // 3. Load the background template (cleaned in prepare_assets)
        $bgPath = public_path('templates/clean-slip-bg.png');
        if (!file_exists($bgPath)) {
            return back()->withErrors(['bg' => 'Background template clean-slip-bg.png is missing.']);
        }
        $image = $manager->read($bgPath);

        // 4. Load Coordinate Mappings from database
        $mappings = ReceiptFieldMapping::all()->keyBy('field_key');

        // 5. Render Basic Fields
        // Status Bar Time
        if ($mapping = $mappings->get('device_time')) {
            $this->drawField($image, $mapping, $validated['device_time']);
        }

        // Net Amount & Asset Combined
        if ($mapping = $mappings->get('net_amount')) {
            $netAmountText = $validated['net_amount'] . ' ' . $validated['net_asset'];
            $this->drawField($image, $mapping, $netAmountText);
        }

        // Network (TRX)
        if ($mapping = $mappings->get('network')) {
            $this->drawField($image, $mapping, $validated['network']);
        }

        // Amount (Gross)
        if ($mapping = $mappings->get('amount')) {
            $grossAmountText = $validated['amount'] . ' ' . $validated['amount_asset'];
            $this->drawField($image, $mapping, $grossAmountText);
        }

        // Network Fee
        if ($mapping = $mappings->get('network_fee')) {
            $feeText = $validated['network_fee'] . ' ' . $validated['fee_asset'];
            $this->drawField($image, $mapping, $feeText);
        }

        // Withdrawal Wallet
        if ($mapping = $mappings->get('withdrawal_wallet')) {
            $this->drawField($image, $mapping, $validated['withdrawal_wallet']);
        }

        // Date
        if ($mapping = $mappings->get('date')) {
            $this->drawField($image, $mapping, $validated['date']);
        }

        // 6. Custom Wrapping & Overlay Rendering for Recipient Address
        if ($mapping = $mappings->get('address')) {
            $addressLines = $this->wrapText($validated['address'], 30);
            foreach ($addressLines as $idx => $line) {
                // Line 1 is offset to the left of the copy icon (ends at x=540)
                // Subsequent lines align to the default right border (ends at x=574)
                $x = ($idx === 0) ? 540 : $mapping->x_coordinate;
                $y = $mapping->y_coordinate + ($idx * 20); // 20px spacing
                $this->drawRawText($image, $line, $x, $y, 'Inter-Medium.ttf', $mapping->font_size, $mapping->font_color, 'right');
            }
        }

        // 7. Custom Wrapping, Overlays & Underlines for TxID
        if ($mapping = $mappings->get('txid')) {
            $txidLines = $this->wrapText($validated['txid'], 31);
            $fontFile = public_path('fonts/Inter-Medium.ttf');

            $width1 = count($txidLines) > 0 ? $this->getTextWidth($txidLines[0], $mapping->font_size, $fontFile) : 0;
            $x_start = 540 - $width1;

            foreach ($txidLines as $idx => $line) {
                $y = $mapping->y_coordinate + ($idx * 20); // 20px spacing
                
                if ($idx < 2) {
                    // Left-align first two lines starting at $x_start to align perfectly on the left
                    $this->drawRawText($image, $line, $x_start, $y, 'Inter-Medium.ttf', $mapping->font_size, $mapping->font_color, 'left');
                    $width = $this->getTextWidth($line, $mapping->font_size, $fontFile);
                    $x_end = $x_start + $width;
                    $y_underline = $y + 19;
                    
                    $image->drawLine(function ($draw) use ($x_start, $x_end, $y_underline, $mapping) {
                        $draw->from($x_start, $y_underline);
                        $draw->to($x_end, $y_underline);
                        $draw->color($mapping->font_color);
                        $draw->width(1);
                    });
                } else {
                    // Right-align remaining lines at 540 (to align with the first two lines, bypassing the copy icon)
                    $x = 540;
                    $this->drawRawText($image, $line, $x, $y, 'Inter-Medium.ttf', $mapping->font_size, $mapping->font_color, 'right');
                    
                    $width = $this->getTextWidth($line, $mapping->font_size, $fontFile);
                    $x_start_line = $x - $width;
                    $y_underline = $y + 19;
                    
                    $image->drawLine(function ($draw) use ($x_start_line, $x, $y_underline, $mapping) {
                        $draw->from($x_start_line, $y_underline);
                        $draw->to($x, $y_underline);
                        $draw->color($mapping->font_color);
                        $draw->width(1);
                    });
                }
            }
        }

        // 8. Place Status Bar Icons Overlays
        // Signal Strength
        $bars = explode('-', $validated['signal_status'])[0];
        $signalIconPath = public_path("images/status-bar/signal-{$bars}-bars.png");
        if (file_exists($signalIconPath)) {
            $image->place($signalIconPath, 'top-left', 416, 20);
        }

        // Battery Status
        $battery = $validated['battery_status'];
        $batteryIconPath = public_path("images/status-bar/battery-{$battery}.png");
        if (file_exists($batteryIconPath)) {
            $image->place($batteryIconPath, 'top-left', 494, 20);
        }

        // Wifi Icon (Static)
        $wifiIconPath = public_path("images/status-bar/wifi_original.png");
        if (file_exists($wifiIconPath)) {
            $image->place($wifiIconPath, 'top-left', 460, 20);
        }

        // 9. Stream compiled image back to client
        $binary = (string) $image->toPng();
        
        return response($binary)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="withdrawal_receipt_' . time() . '.png"');
    }

    /**
     * Helper: Split string by length and manual breaks.
     */
    private function wrapText($text, $maxChars)
    {
        $lines = [];
        $rawLines = explode("\n", str_replace("\r", "", $text));
        foreach ($rawLines as $line) {
            if (mb_strlen($line) <= $maxChars) {
                $lines[] = $line;
            } else {
                for ($i = 0; $i < mb_strlen($line); $i += $maxChars) {
                    $lines[] = mb_substr($line, $i, $maxChars);
                }
            }
        }
        return $lines;
    }

    /**
     * Helper: Get pixel width of TrueType text.
     */
    private function getTextWidth($text, $fontSize, $fontFile)
    {
        $bbox = imagettfbbox($fontSize * 0.75, 0, $fontFile, $text);
        return abs($bbox[2] - $bbox[0]);
    }

    /**
     * Helper: Draw database field based on configuration.
     */
    private function drawField($image, $mapping, $value)
    {
        $fontName = 'Inter-Regular.ttf';
        if ($mapping->font_weight === 'medium') {
            $fontName = 'Inter-Medium.ttf';
        } elseif ($mapping->font_weight === 'bold') {
            $fontName = 'Inter-Bold.ttf';
        } elseif ($mapping->font_weight === 'semibold') {
            $fontName = 'SF-Pro-Text-Semibold.ttf';
        }

        $this->drawRawText(
            $image,
            $value,
            $mapping->x_coordinate,
            $mapping->y_coordinate,
            $fontName,
            $mapping->font_size,
            $mapping->font_color,
            $mapping->text_align
        );
    }

    /**
     * Helper: Draw text with raw configuration.
     */
    private function drawRawText($image, $text, $x, $y, $fontName, $fontSize, $fontColor, $textAlign)
    {
        $fontPath = public_path("fonts/{$fontName}");
        if (!file_exists($fontPath)) {
            $fontPath = public_path("fonts/Inter-Regular.ttf"); // fallback
        }

        $image->text($text, $x, $y, function ($font) use ($fontPath, $fontSize, $fontColor, $textAlign) {
            $font->file($fontPath);
            $font->size($fontSize);
            $font->color($fontColor);
            $font->align($textAlign);
            $font->valign('top'); // match coordinate top-edge logic
        });
    }
}
