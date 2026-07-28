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
                                <div class="flex justify-between items-center mb-1">
                                    <label class="block text-xs font-medium text-[#848e9c]">Battery Level</label>
                                    <span class="text-xs font-bold text-yellow-500" x-text="battery_percent + '%'"></span>
                                </div>
                                <input type="range" min="1" max="100" x-model="battery_percent" 
                                       class="w-full h-2 bg-[#0b0e11] rounded-lg appearance-none cursor-pointer accent-yellow-500 my-2">
                                <input type="hidden" name="battery_status" :value="getBatteryStatus()">
                                <input type="hidden" name="battery_percent" :value="battery_percent">
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
                                <div class="flex justify-between items-center mb-1">
                                    <label class="block text-xs font-medium text-[#848e9c]">Recipient Address (Use \n for manual breaks)</label>
                                    <button type="button" @click="generateRandomAddress()" class="text-xs text-yellow-500 hover:text-yellow-400 font-semibold transition duration-150">Randomly</button>
                                </div>
                                <textarea name="address" x-model="address" rows="2" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm font-mono"></textarea>
                                <p class="text-[10px] text-[#848e9c] mt-1">First line will wrap automatically if exceeding 30 characters.</p>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="block text-xs font-medium text-[#848e9c]">Transaction ID / TxID (Use \n for manual breaks)</label>
                                    <button type="button" @click="generateRandomTxid()" class="text-xs text-yellow-500 hover:text-yellow-400 font-semibold transition duration-150">Randomly</button>
                                </div>
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
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-xs font-medium text-[#848e9c]">Transaction Timestamp</label>
                                <button type="button" @click="setChinaTime()" class="text-xs font-semibold text-yellow-500 hover:text-yellow-400 bg-yellow-500/10 hover:bg-yellow-500/20 px-2.5 py-1 rounded-lg transition duration-150 flex items-center space-x-1 border border-yellow-500/20">
                                    <span>🇨🇳 China Time (UTC+8)</span>
                                </button>
                            </div>
                            <input type="text" name="date" x-model="date" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-xl px-3 py-2 text-white focus:outline-none focus:border-yellow-500 transition duration-200 text-sm">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3 pt-2">
                        <button type="button" 
                                @click="copyGeneratedImage()"
                                :disabled="copying"
                                class="w-full flex justify-center items-center py-3.5 px-6 border border-[#2b3139] rounded-2xl text-base font-bold text-white bg-[#181a20] hover:bg-[#232730] focus:outline-none shadow-md transition duration-150 transform hover:-translate-y-0.5">
                            <svg class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012-2v-8a2 2 0 01-2-2h-8a2 2 0 01-2 2v8a2 2 0 012 2z" />
                            </svg>
                            <span x-text="copying ? 'Copying Image...' : (copied ? '✓ Image Copied to Clipboard!' : 'Copy Image to Clipboard')"></span>
                        </button>

                        <button type="submit" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-2xl text-base font-bold text-[#0b0e11] bg-gradient-to-r from-[#FCD535] to-[#F3BA2F] hover:from-yellow-400 hover:to-yellow-500 focus:outline-none shadow-lg shadow-yellow-500/10 hover:shadow-yellow-500/20 transition duration-150 transform hover:-translate-y-0.5">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Download High-Fidelity Slip (PNG)</span>
                        </button>
                    </div>

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
                        background-image: url('/templates/clean-slip-bg.png?v=5.0');
                        background-size: cover;
                        background-repeat: no-repeat;
                     ">
                     
                     <!-- Status Bar overlays -->
                     <!-- Time on left -->
                     <span class="absolute text-black font-bold preview-text"
                           x-text="device_time"
                           style="left: calc(81 * var(--w-factor)); top: calc(31 * var(--w-factor)); font-size: calc(17 * var(--w-factor)); line-height: 1;"></span>
                      
                     <!-- Cellular Signal bars -->
                     <img :src="'/images/status-bar/signal-' + getSignalNumber() + '-bars.png?v=3.0'"
                          class="absolute"
                          style="left: calc(415 * var(--w-factor)); top: calc(31 * var(--w-factor)); width: calc(31 * var(--w-factor)); height: calc(23 * var(--w-factor));">
                            
                     <!-- Wifi icon (Static) -->
                     <img src="/images/status-bar/wifi_original.png?v=3.0"
                          class="absolute"
                          style="left: calc(455 * var(--w-factor)); top: calc(31 * var(--w-factor)); width: calc(33 * var(--w-factor)); height: calc(22 * var(--w-factor));">

                     <!-- Battery icon -->
                     <img :src="'/images/status-bar/battery-pct-' + battery_percent + '.png?v=3.0'"
                          class="absolute"
                          style="left: calc(495 * var(--w-factor)); top: calc(31 * var(--w-factor)); width: calc(49 * var(--w-factor)); height: calc(23 * var(--w-factor));">

                     <!-- Net Amount -->
                     <span class="absolute text-black font-bold preview-text text-center select-text"
                           x-text="net_amount + ' ' + net_asset"
                           style="left: 50%; transform: translateX(-50%); top: calc(166 * var(--w-factor)); font-size: calc(38 * var(--w-factor)); line-height: 1.1; width: calc(500 * var(--w-factor)); letter-spacing: -0.3px;"></span>

                     <!-- Details Column values -->
                     <!-- Network (TRX) -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="network"
                           style="right: calc(16 * var(--w-factor)); top: calc(454 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>
                            
                     <!-- Address (wrapped) -->
                     <div class="absolute flex flex-col items-end text-right select-text"
                          style="right: calc(16 * var(--w-factor)); top: calc(504 * var(--w-factor)); width: calc(380 * var(--w-factor));">
                          <template x-for="(line, idx) in addressLines" :key="idx">
                              <span class="text-[#1E2329] font-medium preview-text"
                                    x-text="line"
                                    style="margin-right: calc(34 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1.3;"></span>
                          </template>
                     </div>

                     <!-- Address copy icon overlay -->
                     <img src="/images/copy-icon.png?v=2.0" class="absolute"
                          style="left: calc(541 * var(--w-factor)); top: calc(505 * var(--w-factor)); width: calc(21 * var(--w-factor)); height: calc(22 * var(--w-factor));">
                     
                     <!-- Txid (wrapped and underlined) — same right/width as Address so lines end at x=540 -->
                     <div class="absolute flex flex-col items-end text-right select-text"
                          style="right: calc(16 * var(--w-factor)); top: calc(610 * var(--w-factor)); width: calc(380 * var(--w-factor));">
                          <template x-for="(line, idx) in txidLines" :key="idx">
                              <span class="text-[#1E2329] font-medium preview-text border-b border-[#1E2329]/40 pb-[0.5px]"
                                    x-text="line"
                                    style="margin-right: calc(34 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1.3; margin-bottom: 2px;"></span>
                          </template>
                     </div>

                     <!-- Txid copy icon -->
                     <img src="/images/copy-icon.png?v=2.0" class="absolute"
                          style="left: calc(541 * var(--w-factor)); top: calc(611 * var(--w-factor)); width: calc(21 * var(--w-factor)); height: calc(22 * var(--w-factor));">

                     <!-- Amount -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="amount + ' ' + amount_asset"
                           style="right: calc(16 * var(--w-factor)); top: calc(702 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>

                     <!-- Network fee -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="network_fee + ' ' + fee_asset"
                           style="right: calc(16 * var(--w-factor)); top: calc(754 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>

                     <!-- Withdrawal Wallet -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="withdrawal_wallet"
                           style="right: calc(16 * var(--w-factor)); top: calc(806 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>

                     <!-- Date -->
                     <span class="absolute text-[#1E2329] font-medium preview-text text-right select-text"
                           x-text="date"
                           style="right: calc(16 * var(--w-factor)); top: calc(859 * var(--w-factor)); font-size: calc(16 * var(--w-factor)); line-height: 1;"></span>
                </div>
            </div>
        </section>
        
    </main>

    <!-- Quick Copy Image Modal for 100% universal right-click / direct copy -->
    <div x-show="showCopyModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         style="display: none;">
        <div @click.away="showCopyModal = false" class="bg-[#181a20] border border-[#2b3139] rounded-2xl max-w-md w-full p-6 text-center space-y-4 shadow-2xl">
            <div class="flex justify-between items-center border-b border-[#2b3139] pb-3">
                <h3 class="text-base font-bold text-white flex items-center space-x-2">
                    <span class="text-yellow-500">📋</span>
                    <span>Copy Image directly</span>
                </h3>
                <button @click="showCopyModal = false" class="text-gray-400 hover:text-white font-bold text-lg leading-none">&times;</button>
            </div>
            
            <p class="text-xs text-[#848e9c] leading-relaxed">
                Right-click the image below and select <strong class="text-yellow-500">"Copy Image"</strong> to paste (<kbd class="bg-[#2b3139] px-1.5 py-0.5 rounded text-white font-mono">Ctrl+V</kbd>) directly into Telegram, WhatsApp, or WeChat!
            </p>

            <div class="bg-black/50 border border-[#2b3139] rounded-xl p-2 max-h-[440px] overflow-y-auto flex justify-center items-center">
                <img :src="modalImageUrl" id="modalCopyImage" class="max-w-full h-auto rounded shadow select-all cursor-pointer" alt="Binance Receipt Slip">
            </div>

            <div class="flex space-x-3 pt-2">
                <button type="button" @click="copyModalImageDirect()" class="flex-1 bg-yellow-500 hover:bg-yellow-400 text-black font-bold py-2.5 px-4 rounded-xl text-sm transition duration-150">
                    <span x-text="modalCopied ? '✓ Image Copied!' : 'Copy Image'"></span>
                </button>
                <button type="button" @click="showCopyModal = false" class="bg-[#2b3139] hover:bg-[#363c44] text-gray-300 font-bold py-2.5 px-4 rounded-xl text-sm transition duration-150">
                    Done
                </button>
            </div>
        </div>
    </div>

    <!-- Alpine.js script logic -->
    <script>
        function editorApp() {
            return {
                device_time: '',
                battery_percent: 100,
                signal_status: '4-bars',
                net_amount: '-178.5',
                net_asset: 'USDT',
                network: 'TRX',
                address: 'T9z4hQQte8K9Mvy4eFtDvU3TxzseryEDy',
                txid: 'cf28915cb8a90c8bc6559610907fe3e0fc7de757448eef104041917de3797f',
                amount: '180',
                amount_asset: 'USDT',
                network_fee: '1.5',
                fee_asset: 'USDT',
                withdrawal_wallet: 'Spot Account',
                date: '2026-07-11 18:49:04',
                
                copying: false,
                copied: false,
                showCopyModal: false,
                modalImageUrl: '',
                modalCopied: false,
                
                init() {
                    const now = new Date();
                    const h = String(now.getHours()).padStart(2, '0');
                    const m = String(now.getMinutes()).padStart(2, '0');
                    this.device_time = `${h}:${m}`;
                },

                setChinaTime() {
                    const now = new Date();
                    const utc8 = new Date(now.getTime() + (now.getTimezoneOffset() * 60000) + (8 * 3600000));
                    const year = utc8.getFullYear();
                    const month = String(utc8.getMonth() + 1).padStart(2, '0');
                    const day = String(utc8.getDate()).padStart(2, '0');
                    const hours = String(utc8.getHours()).padStart(2, '0');
                    const minutes = String(utc8.getMinutes()).padStart(2, '0');
                    const seconds = String(utc8.getSeconds()).padStart(2, '0');
                    this.date = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                },

                async copyGeneratedImage() {
                    this.copying = true;
                    this.copied = false;
                    
                    if (document.hasFocus && !document.hasFocus()) {
                        window.focus();
                    }

                    try {
                        const formElement = document.querySelector('form');
                        const formData = new FormData(formElement);
                        
                        // Extract fresh CSRF token from form or meta tag
                        const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';
                        formData.set('_token', csrfToken);

                        const response = await fetch('{{ route("generate") }}', {
                            method: 'POST',
                            body: formData,
                            credentials: 'same-origin',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });

                        if (!response.ok) {
                            const errText = await response.text();
                            console.error('Receipt generation server error:', response.status, errText);
                            throw new Error('Server returned HTTP ' + response.status);
                        }

                        const ab = await response.arrayBuffer();
                        const pngBlob = new Blob([ab], { type: 'image/png' });

                        // Try direct Async Clipboard API
                        if (navigator.clipboard && typeof ClipboardItem !== 'undefined') {
                            try {
                                const item = new ClipboardItem({ 'image/png': pngBlob });
                                await navigator.clipboard.write([item]);
                                this.copied = true;
                                setTimeout(() => { this.copied = false; }, 3500);
                                return;
                            } catch (clipErr) {
                                console.warn('Direct ClipboardItem write failed, opening modal:', clipErr);
                            }
                        }

                        // Open Quick Copy Modal for 100% universal right-click copy & selection copy
                        if (this.modalImageUrl) URL.revokeObjectURL(this.modalImageUrl);
                        this.modalImageUrl = URL.createObjectURL(pngBlob);
                        this.showCopyModal = true;
                        this.modalCopied = false;

                    } catch (err) {
                        console.error('Copy image error:', err);
                        alert('Could not generate receipt image: ' + err.message + '. Please check form values.');
                    } finally {
                        this.copying = false;
                    }
                },

                copyModalImageDirect() {
                    const img = document.getElementById('modalCopyImage');
                    if (!img) return;
                    try {
                        const range = document.createRange();
                        range.selectNode(img);
                        const sel = window.getSelection();
                        sel.removeAllRanges();
                        sel.addRange(range);
                        
                        const success = document.execCommand('copy');
                        if (success) {
                            this.modalCopied = true;
                            setTimeout(() => { this.modalCopied = false; }, 3000);
                        } else {
                            alert('Please right-click the image and select "Copy Image".');
                        }
                    } catch (e) {
                        alert('Please right-click the image and select "Copy Image".');
                    }
                },

                getBatteryStatus() {
                    const pct = parseInt(this.battery_percent) || 100;
                    if (pct > 65) return 'full';
                    if (pct > 25) return 'medium';
                    return 'low';
                },

                getSignalNumber() {
                    return this.signal_status.split('-')[0];
                },

                generateRandomAddress() {
                    const chars = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
                    let addr = 'T';
                    for (let i = 0; i < 33; i++) {
                        addr += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    this.address = addr;
                },
                
                generateRandomTxid() {
                    const chars = '0123456789abcdef';
                    let hex = '';
                    for (let i = 0; i < 62; i++) {
                        hex += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    this.txid = hex;
                },
                
                get addressLines() {
                    if (!this.address) return [];
                    if (this.address.includes('\n')) {
                        return this.address.split('\n');
                    }
                    if (this.address.length <= 25) {
                        return [this.address];
                    }
                    return [
                        this.address.substring(0, 25),
                        this.address.substring(25)
                    ];
                },
                
                get txidLines() {
                    if (!this.txid) return [];
                    if (this.txid.includes('\n')) {
                        return this.txid.split('\n');
                    }
                    if (this.txid.length <= 25) {
                        return [this.txid];
                    }
                    if (this.txid.length <= 51) {
                        return [
                            this.txid.substring(0, 25),
                            this.txid.substring(25)
                        ];
                    }
                    return [
                        this.txid.substring(0, 25),
                        this.txid.substring(25, 51),
                        this.txid.substring(51)
                    ];
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
