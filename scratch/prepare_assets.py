import os
import zipfile
from PIL import Image, ImageDraw

# Define directories
workspace_dir = r"c:\xampp\htdocs\Payslip"
fonts_dir = os.path.join(workspace_dir, "public", "fonts")
templates_dir = os.path.join(workspace_dir, "public", "templates")
statusbar_dir = os.path.join(workspace_dir, "public", "images", "status-bar")

os.makedirs(fonts_dir, exist_ok=True)
os.makedirs(templates_dir, exist_ok=True)
os.makedirs(statusbar_dir, exist_ok=True)

# 1. Extract Font Files from the ZIP
zip_path = r"C:\Users\Hamanyoon _AI Engine\.gemini\antigravity\brain\ff06c044-281b-42dd-b27a-f53fdc687c5f\scratch\Inter-4.0.zip"
print(f"Extracting fonts from {zip_path}...")
with zipfile.ZipFile(zip_path, 'r') as zip_ref:
    # Font mappings
    font_mappings = {
        'extras/ttf/Inter-Regular.ttf': 'Inter-Regular.ttf',
        'extras/ttf/Inter-Medium.ttf': 'Inter-Medium.ttf',
        'extras/ttf/Inter-Bold.ttf': 'Inter-Bold.ttf',
        'extras/ttf/Inter-SemiBold.ttf': 'SF-Pro-Text-Semibold.ttf'
    }
    for zip_name, dest_name in font_mappings.items():
        try:
            data = zip_ref.read(zip_name)
            dest_path = os.path.join(fonts_dir, dest_name)
            with open(dest_path, 'wb') as f:
                f.write(data)
            print(f"Extracted {zip_name} -> {dest_path}")
            
            if dest_name == 'SF-Pro-Text-Semibold.ttf':
                with open(os.path.join(fonts_dir, 'Inter-SemiBold.ttf'), 'wb') as f:
                    f.write(data)
        except KeyError:
            print(f"Error: {zip_name} not found in zip")

# 2. Slice authentic status bar icons from the original image
sample_path = os.path.join(workspace_dir, "'payslipsample.jpeg")
if os.path.exists(sample_path):
    print("Slicing authentic status bar icons with exact coordinates...")
    with Image.open(sample_path) as img:
        # A. Cellular Signal Icon: x=416 to 440, y=20 to 50
        signal_crop = img.crop((416, 20, 440, 50)).convert("RGBA")
        sig_data = signal_crop.getdata()
        sig_new = []
        for p in sig_data:
            if p[0] > 240 and p[1] > 240 and p[2] > 240:
                sig_new.append((255, 255, 255, 0))
            else:
                sig_new.append((0, 0, 0, 255))
        signal_crop.putdata(sig_new)
        signal_crop.save(os.path.join(statusbar_dir, "signal_original.png"), "PNG")
        
        # B. Wifi Icon: x=460 to 488, y=20 to 50
        wifi_crop = img.crop((460, 20, 488, 50)).convert("RGBA")
        wifi_data = wifi_crop.getdata()
        wifi_new = []
        for p in wifi_data:
            if p[0] > 240 and p[1] > 240 and p[2] > 240:
                wifi_new.append((255, 255, 255, 0))
            else:
                wifi_new.append((0, 0, 0, 255))
        wifi_crop.putdata(wifi_new)
        wifi_crop.save(os.path.join(statusbar_dir, "wifi_original.png"), "PNG")

        # C. Battery Icon (full): x=494 to 544, y=20 to 50
        battery_crop = img.crop((494, 20, 544, 50)).convert("RGBA")
        bat_data = battery_crop.getdata()
        bat_new = []
        for p in bat_data:
            if p[0] > 240 and p[1] > 240 and p[2] > 240:
                bat_new.append((255, 255, 255, 0))
            else:
                bat_new.append((0, 0, 0, 255))
        battery_crop.putdata(bat_new)
        battery_crop.save(os.path.join(statusbar_dir, "battery-full.png"), "PNG")
        print("Authentic icons sliced and saved successfully.")

# 3. Generate auxiliary cellular signal strength icons programmatically
# Matching the exact 24x30 layout bounds of signal_original.png
def draw_signal_icon(active_bars, filename):
    if filename == "signal-4-bars.png" and os.path.exists(os.path.join(statusbar_dir, "signal_original.png")):
        # Copy authentic 4-bars signal
        import shutil
        shutil.copy(os.path.join(statusbar_dir, "signal_original.png"), os.path.join(statusbar_dir, filename))
        print("Copied authentic signal-4-bars.png")
        return
        
    img = Image.new("RGBA", (24, 30), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    # matching the height and positioning of the original signal crop
    # y base = 29
    bar_heights = [4, 7, 10, 14]
    
    for i in range(4):
        x0 = i * 5
        x1 = x0 + 3
        y1 = 29
        y0 = y1 - bar_heights[i] + 1
        
        color = (0, 0, 0, 255) if i < active_bars else (180, 180, 180, 80)
        draw.rectangle([x0, y0, x1, y1], fill=color)
        
    img.save(os.path.join(statusbar_dir, filename), "PNG")
    print(f"Generated signal icon: {filename}")

for bars in range(1, 5):
    draw_signal_icon(bars, f"signal-{bars}-bars.png")

# 4. Generate auxiliary battery status icons programmatically
# Matching the exact 50x30 layout bounds of battery-full.png
def draw_battery_icon(level, filename):
    if level == "full":
        # battery-full.png is already saved as authentic crop
        return
        
    img = Image.new("RGBA", (50, 30), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    # Draw battery outline (30x12 rectangle centered vertically: y=9 to 20)
    # matching authentic left margin x = 7
    draw.rectangle([7, 9, 36, 20], outline=(0, 0, 0, 255), width=1)
    # Draw battery tip (2px width, 4px height: y=13 to 16)
    draw.rectangle([37, 12, 38, 17], fill=(0, 0, 0, 255))
    
    # Draw inner fill based on level
    if level == "medium":
        # fill width = 13px (from x=9 to 21)
        draw.rectangle([9, 11, 21, 18], fill=(0, 0, 0, 255))
    elif level == "low":
        # fill width = 5px (from x=9 to 13) in red
        draw.rectangle([9, 11, 13, 18], fill=(239, 68, 68, 255))
        
    img.save(os.path.join(statusbar_dir, filename), "PNG")
    print(f"Generated battery icon: {filename}")

draw_battery_icon("medium", "battery-medium.png")
draw_battery_icon("low", "battery-low.png")

# 5. Clean template background using wider bounding boxes to erase original values completely
bg_output_path = os.path.join(templates_dir, "clean-slip-bg.png")
if os.path.exists(sample_path):
    print("Writing clean background image...")
    with Image.open(sample_path) as img:
        img_clean = img.copy()
        draw = ImageDraw.Draw(img_clean)
        
        # Define areas to overwrite with white (RGB: 255, 255, 255)
        white_boxes = [
            (10, 20, 250, 60),    # Clear entire left status time area completely!
            (410, 20, 580, 60),   # Clear entire right status icons area completely!
            (50, 140, 540, 225),  # Net Amount area
            (250, 410, 575, 445),  # Network value
            (200, 460, 540, 518),  # Address value (keep copy icon space)
            (200, 560, 540, 642),  # Txid value (keep copy icon space)
            (250, 660, 575, 692),  # Amount
            (250, 715, 575, 745),  # Network fee
            (250, 760, 575, 795),  # Wallet type
            (250, 815, 575, 850),  # Date
            (540, 488, 575, 518),  # Clear under Address copy icon
            (540, 585, 575, 642),  # Clear under Txid copy icon
        ]
        
        for box in white_boxes:
            draw.rectangle(box, fill=(255, 255, 255))
            
        img_clean.save(bg_output_path, "PNG")
        print(f"Saved clean background to {bg_output_path}")

print("All assets updated successfully.")
