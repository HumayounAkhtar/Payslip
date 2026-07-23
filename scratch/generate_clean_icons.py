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

# 1. Cellular Signal Icons (35x23)
def generate_signal_icon(active_bars, filename):
    img = Image.new("RGBA", (350, 230), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    bar_coords = [
        (45, 140, 95, 200),
        (115, 100, 165, 200),
        (185, 60, 235, 200),
        (255, 20, 305, 200)
    ]
    
    for i, coords in enumerate(bar_coords):
        is_active = i < active_bars
        color = (0, 0, 0, 255) if is_active else (0, 0, 0, 50)
        draw.rounded_rectangle(coords, radius=12, fill=color)
        
    save_scaled_image(img, filename)

for bars in range(1, 5):
    generate_signal_icon(bars, f"signal-{bars}-bars.png")
generate_signal_icon(4, "signal_original.png")

# 2. Wi-Fi Icon (26x22)
def generate_wifi_icon():
    img = Image.new("RGBA", (260, 220), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    center_x, center_y = 130, 175
    draw.ellipse([center_x - 12, center_y - 12, center_x + 12, center_y + 12], fill=(0, 0, 0, 255))
    
    radii = [45, 85, 125]
    start_angle = 220
    end_angle = 320
    
    for r in radii:
        bbox = [center_x - r, center_y - r, center_x + r, center_y + r]
        draw.arc(bbox, start=start_angle, end=end_angle, fill=(0, 0, 0, 255), width=16)
        
        for angle in [start_angle, end_angle]:
            rad = math.radians(angle)
            cx = center_x + r * math.cos(rad)
            cy = center_y + r * math.sin(rad)
            draw.ellipse([cx - 8, cy - 8, cx + 8, cy + 8], fill=(0, 0, 0, 255))
            
    save_scaled_image(img, "wifi_original.png")

generate_wifi_icon()

# 3. Battery Icons (48x24)
def generate_battery_icon(level, filename):
    img = Image.new("RGBA", (480, 240), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    # Battery body outline: x=[30, 410], y=[40, 200], radius=32, width=16
    draw.rounded_rectangle([30, 40, 410, 200], radius=32, outline=(0, 0, 0, 255), width=16)
    
    # Battery tip: x=[425, 90], y=[445, 150], radius=10
    draw.rounded_rectangle([425, 90, 445, 150], radius=10, fill=(0, 0, 0, 255))
    
    # Battery inner fill: x=[46, fill_right], y=[54, 186]
    # Outline inner boundaries are x=[38, 402] and y=[48, 192]
    # This leaves a clean 8px gap on left/right and 6px gap on top/bottom
    if level == "full":
        fill_right = 394
        fill_color = (0, 0, 0, 255)
    elif level == "medium":
        fill_right = 220
        fill_color = (0, 0, 0, 255)
    elif level == "low":
        fill_right = 100
        fill_color = (239, 68, 68, 255)
    else:
        fill_right = 46
        fill_color = (0, 0, 0, 255)
        
    if fill_right > 46:
        draw.rounded_rectangle([46, 54, fill_right, 186], radius=12, fill=fill_color)
        
    save_scaled_image(img, filename)

for level in ["full", "medium", "low"]:
    generate_battery_icon(level, f"battery-{level}.png")

print("All status bar icons generated successfully!")
