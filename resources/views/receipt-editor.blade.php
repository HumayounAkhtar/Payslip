<!DOCTYPE html>
<html lang="en" class="h-full bg-[#0b0e11]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dynamic Receipt Mocking Engine</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .preview-text {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="h-full flex flex-col text-white" x-data="editorApp()">

    <!-- Top Navigation Bar -->
    <header class="bg-[#181a20] border-b border-[#2b3139] px-6 py-4 flex items-center justify-between shadow-md">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 bg-gradient-to-tr from-[#FCD535] to-[#F3BA2F] rounded-xl flex items-center justify-center">
                <svg class="h-6 w-6 text-[#0b0e11]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold tracking-wide">Receipt Mocking Engine</h1>
                <p class="text-xs text-[#848e9c]">Binance Template (590x1280)</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="text-right hidden sm:block">
                <div class="text-xs text-[#848e9c]">Logged in as</div>
                <div class="text-sm font-semibold text-white">{{ Auth::user()->email }}</div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-[#2b3139] hover:bg-[#3d4550] text-sm text-gray-300 font-semibold px-4 py-2 rounded-xl transition duration-150 flex items-center space-x-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-1 flex flex-col lg:flex-row overflow-hidden">
        
        <!-- Left Editor Panel -->
        <section class="w-full lg:w-1/2 p-6 overflow-y-auto border-r border-[#2b3139] bg-[#181a20]/40">
            <div class="max-w-xl mx-auto space-y-6">
                
                <form action="{{ route('generate') }}" method="POST" target="_blank">
                    @csrf
                    
                    <h2 class="text-xl font-bold text-white mb-4 border-b border-[#2b3139] pb-2 flex items-center space-x-2">
                        <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Administrative Fields</span>
                    </h2>

                    <!-- 1. Device Status Controls -->
                    <div class="bg-[#181a20] border border-[#2b3139] rounded-2xl p-5 space-y-4 mb-5 shadow-lg">
                        <h3 class="text-sm font-bold text-yellow-500 uppercase tracking-wider mb-2">Device Status Indicators</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Status Time</label>
                                <input type="text" name="device_time" x-model="device_time" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Battery Level</label>
                                <select name="battery_status" x-model="battery_status" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                                    <option value="full">Full Charge (100%)</option>
                                    <option value="medium">Medium Charge (50%)</option>
                                    <option value="low">Low Charge (15% - Red)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Signal Bars</label>
                                <select name="signal_status" x-model="signal_status" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                                    <option value="4-bars">4 Bars (Strong)</option>
                                    <option value="3-bars">3 Bars</option>
                                    <option value="2-bars">2 Bars</option>
                                    <option value="1-bar">1 Bar (Weak)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Transaction Details -->
                    <div class="bg-[#181a20] border border-[#2b3139] rounded-2xl p-5 space-y-4 mb-5 shadow-lg">
                        <h3 class="text-sm font-bold text-yellow-500 uppercase tracking-wider mb-2">Net Deducted Amount</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Net Amount</label>
                                <input type="text" name="net_amount" x-model="net_amount" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Asset Symbol</label>
                                <input type="text" name="net_asset" x-model="net_asset" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Blockchain Parameters -->
                    <div class="bg-[#181a20] border border-[#2b3139] rounded-2xl p-5 space-y-4 mb-5 shadow-lg">
                        <h3 class="text-sm font-bold text-yellow-500 uppercase tracking-wider mb-2">Blockchain Parameters</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Network</label>
                                <input type="text" name="network" x-model="network" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Recipient Address (Use \n for manual breaks)</label>
                                <textarea name="address" x-model="address" rows="2" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm font-mono"></textarea>
                                <p class="text-[10px] text-[#848e9c] mt-1">First line will wrap automatically if exceeding 30 characters.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Transaction ID / TxID (Use \n for manual breaks)</label>
                                <textarea name="txid" x-model="txid" rows="3" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm font-mono"></textarea>
                                <p class="text-[10px] text-[#848e9c] mt-1">Lines will wrap automatically if exceeding 31 characters.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Breakdown & Timestamp -->
                    <div class="bg-[#181a20] border border-[#2b3139] rounded-2xl p-5 space-y-4 mb-6 shadow-lg">
                        <h3 class="text-sm font-bold text-yellow-500 uppercase tracking-wider mb-2">Breakdown & Timestamp</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Gross Amount</label>
                                <input type="text" name="amount" x-model="amount" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Network Fee</label>
                                <input type="text" name="network_fee" x-model="network_fee" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Wallet Type</label>
                                <input type="text" name="withdrawal_wallet" x-model="withdrawal_wallet" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Amount Assets</label>
                                <input type="text" name="amount_asset" x-model="amount_asset" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#848e9c] mb-1">Fee Assets</label>
                                <input type="text" name="fee_asset" x-model="fee_asset" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                            </div>
                        </div>
                        <div class="pt-2">
                            <label class="block text-xs font-medium text-[#848e9c] mb-1">Transaction Timestamp</label>
                            <input type="text" name="date" x-model="date" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button type="submit" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-2xl text-base font-bold text-[#0b0e11] bg-gradient-to-r from-[#FCD535] to-[#F3BA2F] hover:from-yellow-400 hover:to-yellow-500 focus:outline-none shadow-lg shadow-yellow-500/10 hover:shadow-yellow-500/20 transition duration-150 transform hover:-translate-y-0.5">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Download High-Fidelity Slip (PNG)</span>
                    </button>

                </form>

            </div>
        </section>
        
        <!-- Right Live Preview Panel -->
        <section class="w-full lg:w-1/2 p-6 flex justify-center items-start overflow-y-auto bg-[#0b0e11] border-t lg:border-t-0 border-[#2b3139]">
            <div class="sticky top-6 flex flex-col items-center space-y-4 w-full max-w-[390px]" style="container-type: inline-size;">
                <span class="text-xs font-semibold text-[#848e9c] uppercase tracking-wider">Live Real-time Preview</span>
                
                <!-- The Mock Receipt Card (width 390px, height 846px scaled from 590x1280 using container queries) -->
                <div class="relative shadow-2xl overflow-hidden border border-[#2b3139] bg-white select-none shrink-0"
                     style="
                        --w-factor: 0.1695cqw;
                        width: 100cqw;
                        height: calc(1280 * var(--w-factor));
                        border-radius: calc(30 * var(--w-factor));
                        background-image: url('/templates/clean-slip-bg.png');
                        background-size: cover;
                        background-repeat: no-repeat;
                     ">
                     
                     <!-- Status Bar overlays -->
                     <!-- Time on left -->
                     <span class="absolute text-black font-bold preview-text"
                           x-text="device_time"
                           style="left: calc(75 * var(--w-factor)); top: calc(24 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>
                      
                     <!-- Cellular Signal bars -->
                     <img :src="'/images/status-bar/signal-' + getSignalNumber() + '-bars.png'"
                          class="absolute"
                          style="left: calc(416 * var(--w-factor)); top: calc(24 * var(--w-factor)); width: calc(24 * var(--w-factor)); height: calc(30 * var(--w-factor));">
                           
                     <!-- Wifi icon (Static) -->
                     <img src="/images/status-bar/wifi_original.png"
                          class="absolute"
                          style="left: calc(460 * var(--w-factor)); top: calc(24 * var(--w-factor)); width: calc(28 * var(--w-factor)); height: calc(30 * var(--w-factor));">

                     <!-- Battery icon -->
                     <img :src="'/images/status-bar/battery-' + battery_status + '.png'"
                          class="absolute"
                          style="left: calc(494 * var(--w-factor)); top: calc(24 * var(--w-factor)); width: calc(50 * var(--w-factor)); height: calc(30 * var(--w-factor));">

                     <!-- Net Amount -->
                     <span class="absolute text-black font-bold preview-text text-center select-text"
                           x-text="net_amount + ' ' + net_asset"
                           style="left: 50%; transform: translateX(-50%); top: calc(166 * var(--w-factor)); font-size: calc(38 * var(--w-factor)); line-height: 1.1; width: calc(500 * var(--w-factor)); letter-spacing: -0.3px;"></span>

                     <!-- Details Column values -->
                     <!-- Network (TRX) -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="network"
                           style="right: calc(16 * var(--w-factor)); top: calc(416 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>
                           
                     <!-- Address (wrapped) -->
                     <div class="absolute flex flex-col items-end text-right select-text"
                          style="right: calc(16 * var(--w-factor)); top: calc(464 * var(--w-factor)); width: calc(380 * var(--w-factor));">
                          <template x-for="(line, idx) in addressLines" :key="idx">
                              <span class="text-[#1E2329] font-medium preview-text"
                                    x-text="line"
                                    :style="idx === 0 ? 'margin-right: calc(34 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1.3;' : 'font-size: calc(16 * var(--w-factor)); line-height: 1.3; margin-top: 1px;'"></span>
                          </template>
                     </div>
                     
                     <!-- Txid (wrapped and underlined) -->
                     <div class="absolute flex flex-col items-end text-right select-text"
                          style="right: calc(16 * var(--w-factor)); top: calc(561 * var(--w-factor)); width: calc(380 * var(--w-factor));">
                          <!-- First 2 lines container (left-aligned relative to each other, right-aligned to the block) -->
                          <div class="flex flex-col items-start text-left" style="margin-right: calc(34 * var(--w-factor));">
                              <template x-for="(line, idx) in txidLines.slice(0, 2)" :key="idx">
                                  <span class="text-[#1E2329] font-medium preview-text border-b border-[#1E2329]/40 pb-[0.5px]"
                                        x-text="line"
                                        style="font-size: calc(16 * var(--w-factor)); line-height: 1.3; margin-bottom: 2px;"></span>
                              </template>
                          </div>
                          <!-- Remaining lines (right-aligned to the container) -->
                          <template x-for="(line, idx) in txidLines.slice(2)" :key="idx">
                              <span class="text-[#1E2329] font-medium preview-text border-b border-[#1E2329]/40 pb-[0.5px]"
                                    x-text="line"
                                    style="font-size: calc(16 * var(--w-factor)); line-height: 1.3; margin-bottom: 2px;"></span>
                          </template>
                     </div>

                     <!-- Amount -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="amount + ' ' + amount_asset"
                           style="right: calc(16 * var(--w-factor)); top: calc(664 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>

                     <!-- Network fee -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="network_fee + ' ' + fee_asset"
                           style="right: calc(16 * var(--w-factor)); top: calc(720 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>

                     <!-- Withdrawal Wallet -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="withdrawal_wallet"
                           style="right: calc(16 * var(--w-factor)); top: calc(768 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>

                     <!-- Date -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="date"
                           style="right: calc(16 * var(--w-factor)); top: calc(823 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>
                </div>
            </div>
        </section>
        
    </main>

    <!-- Alpine.js script logic -->
    <script>
        function editorApp() {
            return {
                device_time: '{{ date("H:i") }}',
                battery_status: 'full',
                signal_status: '4-bars',
                net_amount: '-178.5',
                net_asset: 'USDT',
                network: 'TRX',
                address: 'T9z4hQQte8K9Mvy4eFtDvU3TxzYser\nyEDy',
                txid: 'cf28915cb8a90c8bc6559610907fe3e\n0fc7de757448eef104041917de3797f\na9',
                amount: '180',
                amount_asset: 'USDT',
                network_fee: '1.5',
                fee_asset: 'USDT',
                withdrawal_wallet: 'Spot Account',
                date: '{{ date("Y-m-d H:i:s") }}',
                
                getSignalNumber() {
                    return this.signal_status.split('-')[0];
                },
                
                get addressLines() {
                    return this.getWrappedLines(this.address, 30);
                },
                
                get txidLines() {
                    return this.getWrappedLines(this.txid, 31);
                },
                
                getWrappedLines(text, maxChars) {
                    if (!text) return [];
                    let lines = [];
                    let rawLines = text.split('\n');
                    rawLines.forEach(line => {
                        if (line.length <= maxChars) {
                            lines.push(line);
                        } else {
                            for (let i = 0; i < line.length; i += maxChars) {
                                lines.push(line.substring(i, i + maxChars));
                            }
                        }
                    });
                    return lines;
                }
            }
        }
    </script>
</body>
</html>
