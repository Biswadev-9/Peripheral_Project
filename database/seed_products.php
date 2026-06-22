<?php
declare(strict_types=1);

function seed_extra_products(PDO $pdo): void
{
    $products = [
        'keyboard' => [
            ['Mechanical Keyboard', 'mechanical-keyboard', 'Keychron', 'K2 V2', 84.00, 'Bluetooth / USB-C', 'Available', 16, 'Compact mechanical keyboard with tactile switches and multi-device pairing.', ['Switch' => 'Brown', 'Layout' => '75%', 'Backlight' => 'White LED', 'Warranty' => '1 year'], 'https://source.unsplash.com/900x650/?mechanical-keyboard', 4.9, 1, 1],
            ['Membrane Keyboard', 'membrane-keyboard', 'Dell', 'KB216', 18.00, 'USB-A', 'Available', 48, 'Quiet full-size membrane keyboard for classroom and lab workstations.', ['Layout' => 'Full size', 'Keys' => '104', 'Cable' => '1.8m', 'Warranty' => '1 year'], 'https://source.unsplash.com/900x650/?office-keyboard', 4.3, 0, 1],
            ['Wireless Keyboard', 'wireless-keyboard', 'Logitech', 'K270', 32.00, 'USB Receiver', 'Available', 30, 'Reliable wireless keyboard with long battery life for flexible desk setups.', ['Battery' => '24 months', 'Range' => '10m', 'Layout' => 'Full size'], 'https://source.unsplash.com/900x650/?wireless-keyboard', 4.5, 1, 0],
            ['Gaming Keyboard', 'gaming-keyboard', 'Redragon', 'K552', 49.99, 'USB-A', 'Available', 14, 'RGB gaming keyboard with mechanical switches and compact metal frame.', ['Switch' => 'Blue', 'Lighting' => 'RGB', 'Layout' => 'TKL'], 'https://source.unsplash.com/900x650/?rgb-keyboard', 4.7, 1, 1],
            ['Ergonomic Keyboard', 'ergonomic-keyboard', 'Microsoft', 'Sculpt', 64.50, 'USB Receiver', 'Available', 9, 'Split ergonomic keyboard for comfortable long typing sessions.', ['Design' => 'Split', 'Palm Rest' => 'Cushioned', 'Battery' => 'AAA'], 'https://source.unsplash.com/900x650/?ergonomic-keyboard', 4.4, 0, 0],
            ['Compact Keyboard', 'compact-keyboard', 'Rapoo', 'E9050', 27.75, 'Bluetooth', 'Available', 22, 'Slim compact keyboard for portable lab carts and tablet stations.', ['Layout' => 'Compact', 'Material' => 'Aluminum', 'Battery' => 'Rechargeable'], 'https://source.unsplash.com/900x650/?compact-keyboard', 4.2, 0, 0],
        ],
        'mouse' => [
            ['Wireless Mouse', 'wireless-mouse', 'Logitech', 'M185', 24.99, 'USB Receiver', 'Available', 42, 'Reliable wireless mouse with long battery life and comfortable daily use.', ['DPI' => '1000', 'Battery' => '12 months', 'Warranty' => '1 year'], 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=900&q=80', 4.6, 1, 1],
            ['Wired Mouse', 'wired-mouse', 'Dell', 'MS116', 12.50, 'USB-A', 'Available', 70, 'Simple optical wired mouse for labs and high-turnover classrooms.', ['DPI' => '1000', 'Cable' => '1.8m', 'Warranty' => '1 year'], 'https://source.unsplash.com/900x650/?wired-mouse', 4.3, 0, 1],
            ['Gaming Mouse', 'gaming-mouse', 'Razer', 'DeathAdder Essential', 39.99, 'USB-A', 'Available', 18, 'Ergonomic gaming mouse with programmable buttons and high precision sensor.', ['DPI' => '6400', 'Buttons' => '5', 'Lighting' => 'Green'], 'https://source.unsplash.com/900x650/?gaming-mouse', 4.8, 1, 1],
            ['Bluetooth Mouse', 'bluetooth-mouse', 'Microsoft', 'Modern Mobile', 29.99, 'Bluetooth 5.0', 'Available', 25, 'Slim Bluetooth mouse for portable lab kits and tablet stations.', ['DPI' => '1200', 'Battery' => 'AA', 'Color' => 'Matte black'], 'https://source.unsplash.com/900x650/?bluetooth-mouse', 4.5, 0, 0],
            ['Ergonomic Mouse', 'ergonomic-mouse', 'Anker', 'AK-UBA', 31.00, 'USB Receiver', 'Available', 11, 'Comfort-focused wireless mouse designed to reduce wrist strain.', ['DPI' => '800/1200/1600', 'Buttons' => '6', 'Battery' => 'AAA'], 'https://source.unsplash.com/900x650/?ergonomic-mouse', 4.4, 0, 0],
            ['Vertical Mouse', 'vertical-mouse', 'Logitech', 'Lift', 69.99, 'Bluetooth / USB Receiver', 'Available', 7, 'Vertical ergonomic mouse for long sessions and accessibility-friendly workstations.', ['DPI' => '4000', 'Angle' => '57 degrees', 'Battery' => '24 months'], 'https://source.unsplash.com/900x650/?vertical-mouse', 4.7, 1, 0],
        ],
        'microphone' => [
            ['USB Condenser Microphone', 'usb-condenser-microphone', 'Blue', 'Snowball iCE', 49.99, 'USB', 'Available', 9, 'Clear plug-and-play USB microphone for classes, meetings, and recordings.', ['Pattern' => 'Cardioid', 'Sample Rate' => '44.1kHz', 'Stand' => 'Included'], 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=900&q=80', 4.5, 0, 0],
            ['Lavalier Microphone', 'lavalier-microphone', 'Boya', 'BY-M1', 16.99, '3.5mm', 'Available', 34, 'Clip-on microphone for lectures, video classes, and interviews.', ['Pattern' => 'Omnidirectional', 'Cable' => '6m', 'Power' => 'LR44 battery'], 'https://source.unsplash.com/900x650/?lavalier-microphone', 4.2, 0, 1],
            ['Desktop Gooseneck Microphone', 'desktop-gooseneck-microphone', 'Fifine', 'K052', 28.50, 'USB', 'Available', 18, 'Flexible desktop microphone for language labs and conferencing rooms.', ['Mute' => 'Touch button', 'Pickup' => 'Cardioid', 'Cable' => 'USB'], 'https://source.unsplash.com/900x650/?desktop-microphone', 4.3, 0, 0],
            ['Podcast Microphone Kit', 'podcast-microphone-kit', 'Maono', 'AU-A04', 59.00, 'USB', 'Available', 12, 'Complete microphone kit with boom arm and pop filter for media labs.', ['Pattern' => 'Cardioid', 'Accessories' => 'Boom arm, pop filter', 'Resolution' => '192kHz/24-bit'], 'https://source.unsplash.com/900x650/?podcast-microphone', 4.6, 1, 1],
            ['Wireless Handheld Microphone', 'wireless-handheld-microphone', 'Shure', 'BLX24', 199.00, 'Wireless UHF', 'Available', 5, 'Wireless handheld microphone system for presentations and events.', ['Range' => '90m', 'Receiver' => 'Single channel', 'Battery' => 'AA'], 'https://source.unsplash.com/900x650/?wireless-microphone', 4.7, 1, 0],
            ['Headset Microphone', 'headset-microphone', 'Jabra', 'Evolve 20', 39.00, 'USB-A', 'Available', 27, 'Lightweight headset microphone for online classes and lab communication.', ['Audio' => 'Stereo', 'Controls' => 'Inline', 'Noise Reduction' => 'Passive'], 'https://source.unsplash.com/900x650/?headset-microphone', 4.4, 0, 0],
        ],
        'speaker' => [
            ['Desktop Speaker Pair', 'desktop-speaker-pair', 'Logitech', 'Z120', 19.99, 'USB / 3.5mm', 'Available', 26, 'Compact stereo speakers for basic lab audio playback.', ['Power' => 'USB', 'Output' => '1.2W', 'Controls' => 'Volume knob'], 'https://source.unsplash.com/900x650/?computer-speakers', 4.2, 0, 1],
            ['Bluetooth Speaker', 'bluetooth-speaker', 'JBL', 'Go 3', 39.99, 'Bluetooth 5.1', 'Available', 19, 'Portable Bluetooth speaker for presentations and small group activities.', ['Battery' => '5 hours', 'Water Resistance' => 'IP67', 'Output' => '4.2W'], 'https://source.unsplash.com/900x650/?bluetooth-speaker', 4.6, 1, 1],
            ['2.1 Speaker System', 'two-one-speaker-system', 'Creative', 'Pebble Plus', 49.99, 'USB / 3.5mm', 'Available', 10, '2.1 speaker set with subwoofer for multimedia lab stations.', ['Channels' => '2.1', 'Subwoofer' => 'Included', 'Power' => 'USB'], 'https://source.unsplash.com/900x650/?subwoofer-speaker', 4.5, 1, 0],
            ['Conference Speakerphone', 'conference-speakerphone', 'Jabra', 'Speak 510', 119.00, 'USB / Bluetooth', 'Available', 8, 'Portable speakerphone for remote classes and group calls.', ['Microphone' => '360 degrees', 'Battery' => '15 hours', 'Range' => '30m'], 'https://source.unsplash.com/900x650/?speakerphone', 4.7, 1, 0],
            ['Soundbar Speaker', 'soundbar-speaker', 'Dell', 'AC511M', 34.00, 'USB', 'Available', 15, 'Monitor-mounted soundbar for clean desk audio in computer labs.', ['Mount' => 'Monitor clip', 'Power' => 'USB', 'Output' => 'Stereo'], 'https://source.unsplash.com/900x650/?computer-soundbar', 4.1, 0, 0],
            ['High Power Lab Speaker', 'high-power-lab-speaker', 'Edifier', 'R1280T', 109.00, 'RCA / AUX', 'Available', 6, 'Bookshelf speakers for multimedia classrooms and demonstration labs.', ['Output' => '42W', 'Controls' => 'Bass/Treble', 'Remote' => 'Included'], 'https://source.unsplash.com/900x650/?bookshelf-speakers', 4.8, 0, 0],
        ],
        'scanner' => [
            ['Flatbed Scanner', 'flatbed-scanner', 'Canon', 'LiDE 300', 69.99, 'USB', 'Available', 13, 'Slim flatbed scanner for documents, photos, and lab forms.', ['Resolution' => '2400 x 2400 dpi', 'Power' => 'USB', 'Size' => 'A4'], 'https://source.unsplash.com/900x650/?flatbed-scanner', 4.4, 1, 1],
            ['Document Scanner', 'document-scanner', 'Brother', 'ADS-1700W', 249.00, 'USB / Wi-Fi', 'Available', 4, 'Fast document scanner with automatic feeder for office labs.', ['ADF' => '20 sheets', 'Speed' => '25 ppm', 'Duplex' => 'Yes'], 'https://source.unsplash.com/900x650/?document-scanner', 4.7, 1, 0],
            ['Barcode Scanner', 'barcode-scanner', 'Zebra', 'DS2208', 89.00, 'USB', 'Available', 20, '2D barcode scanner for inventory tagging and checkout stations.', ['Scan Type' => '1D/2D', 'Stand' => 'Included', 'Cable' => 'USB'], 'https://source.unsplash.com/900x650/?barcode-scanner', 4.6, 0, 1],
            ['Portable Scanner', 'portable-scanner', 'Epson', 'WorkForce ES-50', 129.00, 'USB', 'Available', 7, 'Lightweight portable scanner for mobile inventory and document tasks.', ['Speed' => '5.5 sec/page', 'Weight' => '270g', 'Size' => 'A4'], 'https://source.unsplash.com/900x650/?portable-scanner', 4.3, 0, 0],
            ['Photo Scanner', 'photo-scanner', 'Epson', 'V39 II', 119.00, 'USB', 'Available', 5, 'High-resolution photo scanner for graphics and media labs.', ['Resolution' => '4800 dpi', 'Stand' => 'Built-in', 'Software' => 'Photo restore'], 'https://source.unsplash.com/900x650/?photo-scanner', 4.5, 0, 0],
            ['Network Scanner', 'network-scanner', 'HP', 'ScanJet Pro N4000', 399.00, 'Ethernet / USB', 'Available', 3, 'Network-capable scanner for shared department document workflows.', ['ADF' => '50 sheets', 'Speed' => '40 ppm', 'Duplex' => 'Yes'], 'https://source.unsplash.com/900x650/?office-scanner', 4.6, 0, 0],
        ],
        'monitor' => [
            ['24 Inch IPS Monitor', '24-inch-ips-monitor', 'ASUS', 'VA24EHE', 149.00, 'HDMI / VGA', 'Available', 14, 'Full HD IPS monitor with wide viewing angles for lab workstations.', ['Resolution' => '1920x1080', 'Refresh Rate' => '75Hz', 'Panel' => 'IPS'], 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80', 4.6, 1, 1],
            ['22 Inch LED Monitor', '22-inch-led-monitor', 'Dell', 'E2222H', 119.00, 'DisplayPort / VGA', 'Available', 24, 'Energy-efficient LED monitor for standard lab stations.', ['Resolution' => '1920x1080', 'Panel' => 'VA', 'Refresh Rate' => '60Hz'], 'https://source.unsplash.com/900x650/?computer-monitor', 4.3, 0, 1],
            ['27 Inch QHD Monitor', '27-inch-qhd-monitor', 'LG', '27QN600', 249.00, 'HDMI / DisplayPort', 'Available', 8, 'QHD display for programming, design, and data analysis labs.', ['Resolution' => '2560x1440', 'Panel' => 'IPS', 'Color' => 'sRGB 99%'], 'https://source.unsplash.com/900x650/?qhd-monitor', 4.7, 1, 0],
            ['Curved Monitor', 'curved-monitor', 'Samsung', 'CF390', 189.00, 'HDMI / VGA', 'Available', 11, 'Curved Full HD monitor for immersive workstation setups.', ['Size' => '27 inch', 'Curvature' => '1800R', 'Panel' => 'VA'], 'https://source.unsplash.com/900x650/?curved-monitor', 4.5, 0, 0],
            ['Touchscreen Monitor', 'touchscreen-monitor', 'Acer', 'T232HL', 319.00, 'HDMI / USB', 'Available', 4, 'Touch-enabled monitor for interactive labs and kiosks.', ['Touch' => '10-point', 'Resolution' => '1920x1080', 'Stand' => 'Adjustable'], 'https://source.unsplash.com/900x650/?touchscreen-monitor', 4.4, 0, 0],
            ['144Hz Gaming Monitor', '144hz-gaming-monitor', 'AOC', '24G2', 229.00, 'HDMI / DisplayPort', 'Available', 6, 'High-refresh monitor for simulation, graphics, and gaming labs.', ['Refresh Rate' => '144Hz', 'Panel' => 'IPS', 'Response' => '1ms'], 'https://source.unsplash.com/900x650/?gaming-monitor', 4.8, 1, 1],
        ],
        'printer' => [
            ['Laser Printer', 'laser-printer', 'HP', 'LaserJet M111w', 119.00, 'USB / Wi-Fi', 'Available', 5, 'Compact laser printer with wireless support and fast monochrome output.', ['Speed' => '21 ppm', 'Connectivity' => 'USB, Wi-Fi', 'Duty Cycle' => '8000 pages'], 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=900&q=80', 4.2, 0, 0],
            ['Inkjet Printer', 'inkjet-printer', 'Canon', 'PIXMA G2020', 159.00, 'USB', 'Available', 9, 'Refillable ink tank printer for color handouts and lab documents.', ['Type' => 'Ink tank', 'Print' => 'Color', 'Functions' => 'Print/Scan/Copy'], 'https://source.unsplash.com/900x650/?inkjet-printer', 4.4, 1, 1],
            ['All-in-One Printer', 'all-in-one-printer', 'Epson', 'EcoTank L3250', 199.00, 'USB / Wi-Fi', 'Available', 7, 'Wireless all-in-one printer for shared departmental use.', ['Functions' => 'Print/Scan/Copy', 'Ink' => 'EcoTank', 'Mobile Print' => 'Yes'], 'https://source.unsplash.com/900x650/?all-in-one-printer', 4.6, 1, 1],
            ['Duplex Printer', 'duplex-printer', 'Brother', 'HL-L2321D', 139.00, 'USB', 'Available', 6, 'Automatic duplex monochrome printer for efficient document printing.', ['Duplex' => 'Automatic', 'Speed' => '30 ppm', 'Tray' => '250 sheets'], 'https://source.unsplash.com/900x650/?laser-printer', 4.5, 0, 0],
            ['Network Printer', 'network-printer', 'HP', 'LaserJet Pro M404dn', 289.00, 'Ethernet / USB', 'Available', 4, 'Network-ready printer for computer lab office workflows.', ['Network' => 'Ethernet', 'Speed' => '40 ppm', 'Security' => 'PIN printing'], 'https://source.unsplash.com/900x650/?network-printer', 4.7, 0, 0],
            ['Photo Printer', 'photo-printer', 'Canon', 'Selphy CP1500', 129.00, 'USB-C / Wi-Fi', 'Available', 10, 'Compact photo printer for media projects and ID photo needs.', ['Print Size' => '4x6 inch', 'Connectivity' => 'Wi-Fi, USB-C', 'Technology' => 'Dye sublimation'], 'https://source.unsplash.com/900x650/?photo-printer', 4.3, 0, 0],
        ],
        'projector' => [
            ['Classroom Projector', 'classroom-projector', 'Epson', 'EB-E01', 329.00, 'HDMI / VGA / USB', 'Under Maintenance', 2, 'Bright projector suitable for classrooms and presentation labs.', ['Brightness' => '3300 lumens', 'Resolution' => 'XGA', 'Lamp Life' => '12000 hours'], 'https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?auto=format&fit=crop&w=900&q=80', 4.1, 0, 0],
            ['Full HD Projector', 'full-hd-projector', 'BenQ', 'MH550', 499.00, 'HDMI / VGA', 'Available', 5, 'Full HD projector for detailed lectures and multimedia presentations.', ['Resolution' => '1920x1080', 'Brightness' => '3500 lumens', 'Contrast' => '20000:1'], 'https://source.unsplash.com/900x650/?projector', 4.6, 1, 1],
            ['Portable Mini Projector', 'portable-mini-projector', 'Anker', 'Nebula Capsule', 299.00, 'HDMI / Wi-Fi', 'Available', 8, 'Compact projector for mobile presentations and small study groups.', ['Battery' => '4 hours', 'Speaker' => 'Built-in', 'Resolution' => '854x480'], 'https://source.unsplash.com/900x650/?mini-projector', 4.4, 0, 0],
            ['Short Throw Projector', 'short-throw-projector', 'Optoma', 'W319ST', 629.00, 'HDMI / VGA', 'Available', 3, 'Short throw projector for small classrooms with limited space.', ['Throw' => 'Short', 'Brightness' => '4000 lumens', 'Resolution' => 'WXGA'], 'https://source.unsplash.com/900x650/?short-throw-projector', 4.5, 0, 0],
            ['Laser Projector', 'laser-projector', 'ViewSonic', 'LS500WHE', 899.00, 'HDMI / USB', 'Available', 2, 'Long-life laser projector for high-usage labs and auditoriums.', ['Light Source' => 'Laser', 'Life' => '30000 hours', 'Brightness' => '3000 lumens'], 'https://source.unsplash.com/900x650/?laser-projector', 4.8, 1, 0],
            ['Interactive Projector', 'interactive-projector', 'Epson', 'BrightLink 725Wi', 1499.00, 'HDMI / USB / LAN', 'Available', 1, 'Interactive projector for smart classroom collaboration.', ['Touch' => 'Interactive pen', 'Resolution' => 'WXGA', 'Network' => 'LAN'], 'https://source.unsplash.com/900x650/?interactive-projector', 4.7, 0, 0],
        ],
        'camera' => [
            ['HD Webcam', 'hd-webcam', 'Logitech', 'C270', 29.99, 'USB-A', 'Available', 32, 'HD webcam for online classes, meetings, and lab video stations.', ['Resolution' => '720p', 'Microphone' => 'Built-in', 'Mount' => 'Universal clip'], 'https://source.unsplash.com/900x650/?webcam', 4.3, 0, 1],
            ['Full HD Webcam', 'full-hd-webcam', 'Logitech', 'C920', 79.99, 'USB-A', 'Available', 18, 'Full HD webcam with autofocus for high-quality video sessions.', ['Resolution' => '1080p', 'Autofocus' => 'Yes', 'Microphones' => 'Dual'], 'https://source.unsplash.com/900x650/?hd-webcam', 4.8, 1, 1],
            ['Document Camera', 'document-camera', 'IPEVO', 'V4K', 119.00, 'USB', 'Available', 7, 'Document camera for showing books, circuit boards, and written work.', ['Resolution' => '8MP', 'Focus' => 'Fast autofocus', 'Arm' => 'Multi-jointed'], 'https://source.unsplash.com/900x650/?document-camera', 4.6, 1, 0],
            ['Conference Camera', 'conference-camera', 'Jabra', 'PanaCast 20', 219.00, 'USB-C', 'Available', 5, 'AI-enabled conference camera for group classroom video calls.', ['Resolution' => '4K', 'Field of View' => '90 degrees', 'AI' => 'Intelligent zoom'], 'https://source.unsplash.com/900x650/?conference-camera', 4.7, 0, 0],
            ['Security Camera', 'security-camera', 'TP-Link', 'Tapo C200', 34.99, 'Wi-Fi', 'Available', 12, 'Pan-tilt security camera for monitoring lab rooms and equipment zones.', ['Resolution' => '1080p', 'Pan/Tilt' => 'Yes', 'Storage' => 'microSD'], 'https://source.unsplash.com/900x650/?security-camera', 4.4, 0, 0],
            ['DSLR Camera', 'dslr-camera', 'Canon', 'EOS 2000D', 449.00, 'USB / HDMI', 'Available', 2, 'Entry-level DSLR camera for media labs and photography coursework.', ['Sensor' => '24.1MP APS-C', 'Lens' => '18-55mm', 'Video' => 'Full HD'], 'https://source.unsplash.com/900x650/?dslr-camera', 4.6, 0, 0],
        ],
        'joystick' => [
            ['USB Joystick', 'usb-joystick', 'Logitech', 'Extreme 3D Pro', 39.99, 'USB-A', 'Available', 14, 'Precision joystick for flight simulation and control experiments.', ['Buttons' => '12', 'Throttle' => 'Yes', 'Twist Rudder' => 'Yes'], 'https://source.unsplash.com/900x650/?joystick', 4.5, 1, 1],
            ['Game Controller', 'game-controller', 'Microsoft', 'Xbox Wireless Controller', 59.99, 'Bluetooth / USB-C', 'Available', 22, 'Wireless game controller for game development and testing labs.', ['Connectivity' => 'Bluetooth, USB-C', 'Battery' => 'AA', 'Vibration' => 'Yes'], 'https://source.unsplash.com/900x650/?game-controller', 4.8, 1, 1],
            ['Arcade Joystick', 'arcade-joystick', 'Hori', 'Fighting Stick Mini', 49.99, 'USB', 'Available', 8, 'Compact arcade stick for interactive media and game labs.', ['Buttons' => '8', 'Platform' => 'PC compatible', 'Cable' => 'USB'], 'https://source.unsplash.com/900x650/?arcade-stick', 4.3, 0, 0],
            ['Flight Yoke System', 'flight-yoke-system', 'Logitech', 'G Pro Flight Yoke', 169.00, 'USB', 'Available', 3, 'Flight yoke and throttle system for aviation simulation training.', ['Axes' => '5', 'Throttle' => 'Included', 'Mount' => 'Desk clamp'], 'https://source.unsplash.com/900x650/?flight-yoke', 4.7, 1, 0],
            ['Racing Wheel', 'racing-wheel', 'Thrustmaster', 'T150', 229.00, 'USB', 'Available', 4, 'Force-feedback racing wheel for simulation and gaming coursework.', ['Rotation' => '1080 degrees', 'Pedals' => 'Included', 'Feedback' => 'Force feedback'], 'https://source.unsplash.com/900x650/?racing-wheel', 4.6, 0, 0],
            ['VR Controller Pair', 'vr-controller-pair', 'Meta', 'Quest Touch', 149.00, 'Wireless', 'Available', 6, 'Wireless VR controllers for immersive computing and design labs.', ['Tracking' => '6DoF', 'Battery' => 'AA', 'Pair' => 'Left and right'], 'https://source.unsplash.com/900x650/?vr-controller', 4.4, 0, 0],
        ],
        'network-adapter' => [
            ['USB Wi-Fi Adapter', 'usb-wifi-adapter', 'TP-Link', 'Archer T3U', 19.99, 'USB 3.0', 'Available', 36, 'Dual-band USB Wi-Fi adapter for desktops and lab recovery kits.', ['Speed' => 'AC1300', 'Bands' => '2.4GHz/5GHz', 'USB' => '3.0'], 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=900&q=80', 4.4, 1, 0],
            ['Gigabit Ethernet Adapter', 'gigabit-ethernet-adapter', 'Ugreen', 'CM209', 14.99, 'USB 3.0', 'Available', 28, 'USB to Gigabit Ethernet adapter for reliable wired connectivity.', ['Speed' => '1000Mbps', 'Chipset' => 'RTL8153', 'Cable' => 'Built-in'], 'https://source.unsplash.com/900x650/?ethernet-adapter', 4.5, 0, 1],
            ['USB-C Ethernet Adapter', 'usb-c-ethernet-adapter', 'Anker', 'A83130A1', 22.99, 'USB-C', 'Available', 17, 'Compact USB-C to Ethernet adapter for modern laptops.', ['Speed' => '1Gbps', 'Body' => 'Aluminum', 'Compatibility' => 'Windows/macOS'], 'https://source.unsplash.com/900x650/?usb-c-adapter', 4.6, 1, 0],
            ['Bluetooth Adapter', 'bluetooth-adapter', 'TP-Link', 'UB500', 9.99, 'USB-A', 'Available', 44, 'Bluetooth 5.0 adapter for keyboards, mice, headsets, and controllers.', ['Bluetooth' => '5.0', 'Range' => '20m', 'Profile' => 'A2DP/HID'], 'https://source.unsplash.com/900x650/?bluetooth-adapter', 4.3, 0, 1],
            ['PCIe Wi-Fi Card', 'pcie-wifi-card', 'ASUS', 'PCE-AX3000', 49.99, 'PCIe', 'Available', 10, 'Internal Wi-Fi 6 and Bluetooth adapter for desktop workstations.', ['Wi-Fi' => 'AX3000', 'Bluetooth' => '5.0', 'Antenna' => 'External'], 'https://source.unsplash.com/900x650/?wifi-card', 4.7, 1, 0],
            ['USB Network Adapter Hub', 'usb-network-adapter-hub', 'Baseus', 'Lite Series 4-in-1', 34.99, 'USB-C', 'Available', 13, 'Multiport hub with Ethernet and USB ports for lab laptops.', ['Ethernet' => '1Gbps', 'Ports' => '3 USB-A', 'Body' => 'Aluminum'], 'https://source.unsplash.com/900x650/?usb-hub-ethernet', 4.4, 0, 0],
        ],
        'accessories' => [
            ['USB-C Hub', 'usb-c-hub', 'Anker', '341', 39.99, 'USB-C', 'Available', 24, 'Multiport USB-C hub for connecting displays, drives, and peripherals.', ['Ports' => '7-in-1', 'HDMI' => '4K', 'Power Delivery' => '85W'], 'https://source.unsplash.com/900x650/?usb-c-hub', 4.7, 1, 1],
            ['HDMI Cable', 'hdmi-cable', 'Belkin', 'Ultra HD 2m', 8.99, 'HDMI', 'Available', 80, 'Durable HDMI cable for monitors, projectors, and lab displays.', ['Length' => '2m', 'Resolution' => '4K', 'Version' => '2.0'], 'https://source.unsplash.com/900x650/?hdmi-cable', 4.4, 0, 1],
            ['Laptop Stand', 'laptop-stand', 'Rain Design', 'mStand', 32.00, 'Accessory', 'Available', 18, 'Aluminum laptop stand for ergonomic lab and instructor desks.', ['Material' => 'Aluminum', 'Height' => '15cm', 'Compatibility' => '10-17 inch'], 'https://source.unsplash.com/900x650/?laptop-stand', 4.6, 0, 0],
            ['Cable Organizer Kit', 'cable-organizer-kit', 'JOTO', 'Desk Kit', 11.50, 'Accessory', 'Available', 50, 'Cable clips and sleeves to keep computer lab desks tidy.', ['Items' => 'Clips, sleeves, ties', 'Color' => 'Black', 'Reusable' => 'Yes'], 'https://source.unsplash.com/900x650/?cable-organizer', 4.2, 0, 0],
            ['Mouse Pad', 'mouse-pad', 'SteelSeries', 'QcK Medium', 9.99, 'Accessory', 'Available', 64, 'Smooth cloth mouse pad for consistent tracking and desk protection.', ['Surface' => 'Micro-woven cloth', 'Base' => 'Rubber', 'Size' => 'Medium'], 'https://source.unsplash.com/900x650/?mouse-pad', 4.5, 0, 1],
            ['USB Extension Cable', 'usb-extension-cable', 'Amazon Basics', 'USB 3.0 3m', 7.50, 'USB-A', 'Available', 45, 'USB extension cable for flexible peripheral placement.', ['Length' => '3m', 'Version' => 'USB 3.0', 'Connector' => 'Male to female'], 'https://source.unsplash.com/900x650/?usb-cable', 4.3, 0, 0],
        ],
    ];

    $categoryStmt = $pdo->prepare('SELECT id FROM categories WHERE slug = ?');
    $existsStmt = $pdo->prepare('SELECT id FROM products WHERE slug = ?');
    $fallbackImages = [
        'keyboard' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=900&q=80',
        'mouse' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=900&q=80',
        'microphone' => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=900&q=80',
        'speaker' => 'https://images.unsplash.com/photo-1545454675-3531b543be5d?auto=format&fit=crop&w=900&q=80',
        'scanner' => 'https://images.unsplash.com/photo-1581092335397-9fa341108e1d?auto=format&fit=crop&w=900&q=80',
        'monitor' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80',
        'printer' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=900&q=80',
        'projector' => 'https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?auto=format&fit=crop&w=900&q=80',
        'camera' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=900&q=80',
        'joystick' => 'https://images.unsplash.com/photo-1592840496694-26d035b52b48?auto=format&fit=crop&w=900&q=80',
        'network-adapter' => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=900&q=80',
        'accessories' => 'https://images.unsplash.com/photo-1601524909162-ae8725290836?auto=format&fit=crop&w=900&q=80',
    ];
    $insertStmt = $pdo->prepare(
        'INSERT INTO products (category_id, name, slug, brand, model, price, interface_type, status, stock_quantity, description, specifications, image_url, gallery, rating, is_featured, is_best_seller)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );

    foreach ($products as $categorySlug => $items) {
        $categoryStmt->execute([$categorySlug]);
        $categoryId = $categoryStmt->fetchColumn();
        if (!$categoryId) {
            continue;
        }

        foreach ($items as $item) {
            [$name, $slug, $brand, $model, $price, $interface, $status, $stock, $description, $specs, $image, $rating, $featured, $bestSeller] = $item;
            if (str_starts_with($image, 'https://source.unsplash.com/')) {
                $image = $fallbackImages[$categorySlug];
            }
            $existsStmt->execute([$slug]);
            if ($existsStmt->fetchColumn()) {
                continue;
            }

            $insertStmt->execute([
                $categoryId,
                $name,
                $slug,
                $brand,
                $model,
                $price,
                $interface,
                $status,
                $stock,
                $description,
                json_encode($specs, JSON_UNESCAPED_SLASHES),
                $image,
                json_encode([$image], JSON_UNESCAPED_SLASHES),
                $rating,
                $featured,
                $bestSeller,
            ]);
        }
    }

    $normalizeStmt = $pdo->prepare(
        'UPDATE products p
         JOIN categories c ON c.id = p.category_id
         SET p.image_url = ?, p.gallery = JSON_ARRAY(?)
         WHERE c.slug = ? AND p.image_url LIKE "https://source.unsplash.com/%"'
    );
    foreach ($fallbackImages as $categorySlug => $image) {
        $normalizeStmt->execute([$image, $image, $categorySlug]);
    }
}

if (PHP_SAPI === 'cli' && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    require_once __DIR__ . '/../config/database.php';
    seed_extra_products(Database::connection());
    echo "Product seed completed.\n";
}
