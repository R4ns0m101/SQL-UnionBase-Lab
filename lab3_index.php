<?php
error_reporting(0);
ini_set('display_errors', 0);

// Advanced WAF - keyword removal + hard blocks
// Bypass: Double-write technique (e.g., UNUNIONION -> after removal -> UNION)
function advanced_waf($input) {
    // Phase 1: Hard blocks - completely blocked, no bypass
    $hard_blocks = array('--', '#', '/*', '*/', ';');

    foreach($hard_blocks as $block) {
        if(strpos($input, $block) !== false) {
            error_log("WAF hard-blocked: " . $input);
            return false;
        }
    }

    // Phase 2: Block common boolean injection patterns (e.g., OR 1=1, AND 1=1)
    if(preg_match('/\b(and|or)\b\s+[\d\'\"]+\s*[=<>]\s*[\d\'\"]+/i', $input)) {
        error_log("WAF pattern-blocked: " . $input);
        return false;
    }

    // Phase 3: Keyword removal - removes SQL keywords from input
    // Can be bypassed with double-write: UNUNIONION -> UNION after removal
    $remove_keywords = array(
        // Compound keywords first (prevent partial removal conflicts)
        'INFORMATION_SCHEMA', 'TABLE_SCHEMA', 'TABLE_NAME',
        'COLUMN_NAME', 'GROUP_CONCAT',
        // SQL Keywords
        'UNION', 'SELECT', 'WHERE', 'FROM',
        'INSERT', 'UPDATE', 'DELETE', 'DROP',
        'CREATE', 'ALTER', 'EXECUTE', 'EXEC',
        // Schema keywords
        'COLUMNS', 'TABLES',
        // Functions
        'CONCAT', 'SUBSTRING', 'CHAR', 'ASCII'
    );

    $filtered = $input;
    foreach($remove_keywords as $word) {
        $filtered = preg_replace('/' . preg_quote($word, '/') . '/i', '', $filtered);
    }

    return $filtered;
}

function safe_output($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3 - Advanced Union SQL Injection</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #0f0f1e;
            color: #fff;
            min-height: 100vh;
            padding: 20px;
        }
        
        .cyber-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(45deg, transparent 30%, rgba(0,255,255,0.05) 30%),
                linear-gradient(-45deg, transparent 30%, rgba(255,0,255,0.05) 30%);
            background-size: 60px 60px;
            z-index: -1;
        }
        
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: rgba(20, 20, 40, 0.95);
            border-radius: 20px;
            box-shadow: 0 0 50px rgba(0,255,255,0.3);
            border: 2px solid rgba(0,255,255,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 40px;
            text-align: center;
            border-bottom: 2px solid #00ffff;
        }
        
        .header h1 {
            font-size: 2.5em;
            text-shadow: 0 0 20px #00ffff;
            margin-bottom: 10px;
        }
        
        .security-level {
            display: inline-block;
            background: #ff0066;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9em;
            font-weight: bold;
            margin-top: 10px;
            box-shadow: 0 0 15px #ff0066;
        }
        
        .content {
            padding: 40px;
        }
        
        .search-section {
            background: rgba(30, 30, 50, 0.7);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid rgba(0,255,255,0.2);
            margin-bottom: 30px;
        }
        
        input[type="text"] {
            width: 70%;
            padding: 15px;
            font-size: 16px;
            background: rgba(20, 20, 40, 0.8);
            border: 2px solid #00ffff;
            border-radius: 10px;
            color: #fff;
            outline: none;
        }
        
        input[type="text"]:focus {
            box-shadow: 0 0 15px rgba(0,255,255,0.5);
        }
        
        button {
            padding: 15px 40px;
            font-size: 16px;
            background: linear-gradient(135deg, #00ffff 0%, #ff00ff 100%);
            color: #000;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            margin-left: 10px;
            transition: all 0.3s;
        }
        
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0,255,255,0.6);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(30, 30, 50, 0.5);
            border-radius: 10px;
            overflow: hidden;
        }
        
        th {
            background: rgba(0,255,255,0.2);
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #00ffff;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid rgba(0,255,255,0.1);
        }
        
        tr:hover {
            background: rgba(0,255,255,0.1);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border-color: #28a745;
            color: #4ade80;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border-color: #dc3545;
            color: #f87171;
        }
        
        .alert-info {
            background: rgba(23, 162, 184, 0.2);
            border-color: #17a2b8;
            color: #22d3ee;
        }
        
        .hints-box {
            background: rgba(50, 50, 70, 0.8);
            padding: 25px;
            border-radius: 15px;
            border: 2px solid rgba(255,193,7,0.3);
            margin-top: 30px;
        }
        
        .hints-box h3 {
            color: #ffc107;
            margin-bottom: 15px;
            text-shadow: 0 0 10px #ffc107;
        }
        
        .hints-box ul {
            margin-left: 20px;
            color: #e0e0e0;
        }
        
        .hints-box li {
            margin: 10px 0;
            line-height: 1.6;
        }
        
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: rgba(108, 117, 125, 0.8);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: rgba(90, 98, 104, 0.8);
            box-shadow: 0 0 10px rgba(108, 117, 125, 0.5);
        }
        
        .flag-hint {
            background: linear-gradient(135deg, #ff0066 0%, #ff6600 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 0 20px rgba(255,0,102,0.5);
        }
        
        code {
            background: rgba(0,0,0,0.5);
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #00ffff;
        }
        
        strong {
            color: #00ffff;
        }
    </style>
</head>
<body>
    <div class="cyber-bg"></div>
    <div class="container">
        <div class="header">
            <a href="../" class="back-btn">← กลับหน้าหลัก</a>
            <h1>⚡ Lab 3: Advanced Union-based SQL Injection</h1>
            <div class="security-level">
                ⭐⭐⭐ ระดับ: ยาก | Advanced WAF | Blind Techniques Required
            </div>
        </div>
        
        <div class="content">
            <div class="search-section">
                <h2 style="color: #00ffff; margin-bottom: 15px;">🎯 Advanced Order Tracking System</h2>
                <p style="color: #aaa; margin-bottom: 20px;">ค้นหาคำสั่งซื้อด้วย Order ID (1-5)</p>
                
                <form method="GET">
                    <input type="text" name="id" placeholder="กรอก Order ID เช่น 1, 2, 3, 4, 5" 
                           value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                    <button type="submit">🔎 SEARCH</button>
                </form>
            </div>

            <?php
            if(isset($_GET['id'])) {
                $id = $_GET['id'];

                // ตรวจสอบ Advanced WAF
                $waf_result = advanced_waf($id);
                if($waf_result === false) {
                    echo '<div class="alert alert-danger">';
                    echo '🚨 <strong>SECURITY ALERT!</strong> Malicious pattern detected and blocked by Advanced WAF.<br>';
                    echo '<small>Incident has been logged and security team notified.</small>';
                    echo '</div>';
                } else {
                    // Use WAF-filtered input (keywords removed)
                    $id = $waf_result;
                    $conn = new mysqli("mysql", "webapp3", "webapp3complex!", "lab3_ecommerce");

                    if ($conn->connect_error) {
                        echo '<div class="alert alert-danger">System error. Please try again later.</div>';
                    } else {
                        // Vulnerable query - แต่ไม่แสดง error message
                        $query = "SELECT order_id, customer_name, product_name, total_price FROM orders WHERE order_id = $id";
                        $result = @$conn->query($query);

                        // Blind-like behavior - ไม่แสดง error
                        if($result && $result->num_rows > 0) {
                            echo '<div class="alert alert-info">';
                            echo '✅ Order found in system';
                            echo '</div>';
                            echo '<table>';
                            echo '<tr><th>Order ID</th><th>Customer</th><th>Product</th><th>Price (THB)</th></tr>';

                            while($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>'.safe_output($row['order_id']).'</td>';
                                echo '<td>'.safe_output($row['customer_name']).'</td>';
                                echo '<td>'.safe_output($row['product_name']).'</td>';
                                echo '<td>'.number_format($row['total_price'], 2).'</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                        } else {
                            // Generic error - ไม่บอกรายละเอียด
                            echo '<div class="alert alert-info">';
                            echo 'ℹ️ No results found.';
                            echo '</div>';
                        }

                        $conn->close();
                    }
                }
            }
            ?>
            
            <div class="hints-box">
                <h3>💡 Advanced Challenge Hints:</h3>
                <ul>
                    <li><strong>WAF Behavior:</strong> WAF ของ Lab นี้ไม่ได้แค่ "block" แต่จะ "ลบ" SQL keywords ออกจาก input ลองสังเกตความแตกต่าง</li>
                    <li><strong>Double-Write Technique:</strong> ถ้า WAF ลบ UNION ออก ลองใส่ <code>UNUNIONION</code> หลังลบจะเหลือ <code>UNION</code></li>
                    <li><strong>Hard Blocks:</strong> WAF จะ block comments (<code>--</code>, <code>#</code>, <code>/*</code>) และ semicolons (<code>;</code>) อย่างเด็ดขาด</li>
                    <li><strong>Error Suppression:</strong> Application ไม่แสดง SQL errors ต้องสังเกตจาก response ว่ามีข้อมูลหรือไม่</li>
                    <li><strong>Multiple Databases:</strong> มี 2 databases:
                        <ul style="margin-left: 20px; margin-top: 5px;">
                            <li><code>lab3_ecommerce</code> - orders, payment_methods</li>
                            <li><code>lab3_internal</code> - confidential_files, system_credentials</li>
                        </ul>
                    </li>
                    <li><strong>Target:</strong> Flag อยู่ใน <code>lab3_internal.confidential_files</code></li>
                    <li><strong>Techniques:</strong>
                        <ul style="margin-left: 20px; margin-top: 5px;">
                            <li>Double-write: <code>SESELECTLECT</code>, <code>FRFROMOM</code>, <code>WHWHEREERE</code></li>
                            <li>Hex Encoding for strings: <code>0x6c6162335f696e7465726e616c</code> = "lab3_internal"</li>
                            <li>Cross-database query: <code>db_name.table_name</code></li>
                        </ul>
                    </li>
                </ul>

                <div class="flag-hint">
                    🏁 Ultimate Goal: Discover WAF behavior → Double-write bypass → Cross-DB query → Extract flag from confidential_files
                </div>
            </div>
        </div>
    </div>
</body>
</html>
