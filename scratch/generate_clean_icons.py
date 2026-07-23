import os
import math
from PIL import Image, ImageDraw

statusbar_dir = r"c:\xampp\htdocs\Payslip\public\images\status-bar"
os.makedirs(statusbar_dir, exist_ok=True)

# Helper to scale down a high-res image using LANCZOS
def save_scaled_image(img_large, filename):
    w, h = img_large.size
    img_small = img_large.resize((w // 10, h // 10), Image.Resampling.LANCZOS)
    dest_path = os.path.join(statusbar_dir, filename)
    img_small.save(dest_path, "PNG")
    print(f"Generated {filename} ({img_small.size[0]}x{img_small.size[1]})")

# 1. Cellular Signal Icons (35x23) - Bold style
def generate_signal_icon(active_bars, filename):
    # Canvas size 350x230
    img = Image.new("RGBA", (350, 230), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    # Bolder bars (width 56, gap 14, radius 14)
    bar_coords = [
        (42, 140, 98, 200),
        (112, 100, 168, 200),
        (182, 60, 238, 200),
        (252, 20, 308, 200)
    ]
    
    for i, coords in enumerate(bar_coords):
        is_active = i < active_bars
        color = (0, 0, 0, 255) if is_active else (0, 0, 0, 50)
        draw.rounded_rectangle(coords, radius=14, fill=color)
        
    save_scaled_image(img, filename)

# Generate signal icons
for bars in range(1, 5):
    generate_signal_icon(bars, f"signal-{bars}-bars.png")
generate_signal_icon(4, "signal_original.png")

# 2. Wi-Fi Icon (26x22) - Bold style
def generate_wifi_icon():
    # Canvas size 260x220
    img = Image.new("RGBA", (260, 220), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    center_x, center_y = 130, 175
    
    # Bolder bottom dot: radius 16
    draw.ellipse([center_x - 16, center_y - 16, center_x + 16, center_y + 16], fill=(0, 0, 0, 255))
    
    # Bolder concentric arcs: width 24, gap 16
    radii = [55, 95, 135]
    start_angle = 220
    end_angle = 320
    
    for r in radii:
        bbox = [center_x - r, center_y - r, center_x + r, center_y + r]
        draw.arc(bbox, start=start_angle, end=end_angle, fill=(0, 0, 0, 255), width=24)
        
        # Rounded end caps (radius 12)
        for angle in [start_angle, end_angle]:
            rad = math.radians(angle)
            cx = center_x + r * math.cos(rad)
            cy = center_y + r * math.sin(rad)
            draw.ellipse([cx - 12, cy - 12, cx + 12, cy + 12], fill=(0, 0, 0, 255))
            
    save_scaled_image(img, "wifi_original.png")

generate_wifi_icon()

# 3. Battery Icons (48x24) - Bold style
def generate_battery_icon(level, filename):
    # Canvas size 480x240
    img = Image.new("RGBA", (480, 240), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    # Bolder battery body outline: width 24, radius 32
    draw.rounded_rectangle([30, 40, 410, 200], radius=32, outline=(0, 0, 0, 255), width=24)
    
    # Bolder battery tip: radius 12
    draw.rounded_rectangle([420, 85, 445, 155], radius=12, fill=(0, 0, 0, 255))
    
    # Battery inner fill: x=[50, fill_right], y=[58, 182]
    # Leaves a balanced, clean 8px gap on left/right and 6px gap on top/bottom
    if level == "full":
        fill_right = 390
        fill_color = (0, 0, 0, 255)
    elif level == "medium":
        fill_right = 220
        fill_color = (0, 0, 0, 255)
    elif level == "low":
        fill_right = 100
        fill_color = (239, 68, 68, 255)
    else:
        fill_right = 50
        fill_color = (0, 0, 0, 255)
        
    if fill_right > 50:
        draw.rounded_rectangle([50, 58, fill_right, 182], radius=12, fill=fill_color)
        
    save_scaled_image(img, filename)

for level in ["full", "medium", "low"]:
    generate_battery_icon(level, f"battery-{level}.png")

print("All status bar icons generated successfully!")
