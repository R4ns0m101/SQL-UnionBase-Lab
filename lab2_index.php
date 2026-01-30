<?php
error_reporting(0);

// Basic WAF - filter exact uppercase and lowercase keywords only
// Bypass: Use mixed case like UnIoN, SeLeCt or comment injection like UN/**/ION
function basic_waf($input) {
    $blacklist = array(
        'UNION', 'SELECT', 'WHERE', 'FROM',
        'INFORMATION_SCHEMA', 'TABLE', 'COLUMN',
        'union', 'select', 'where', 'from',
        'information_schema', 'table', 'column'
    );

    foreach($blacklist as $word) {
        if(strpos($input, $word) !== false) {
            return false;
        }
    }
    return true;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2 - Intermediate Union SQL Injection</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .level-badge {
            display: inline-block;
            background: rgba(255,255,255,0.3);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-top: 10px;
        }
        
        .waf-indicator {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.85em;
            margin-left: 10px;
        }
        
        .content {
            padding: 40px;
        }
        
        .search-box {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }
        
        .search-box h2 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .input-group {
            display: flex;
            gap: 10px;
        }
        
        input[type="text"] {
            flex: 1;
            padding: 15px;
            font-size: 16px;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus {
            border-color: #fc4a1a;
        }
        
        button {
            padding: 15px 40px;
            font-size: 16px;
            background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(252, 74, 26, 0.4);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        th {
            background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .alert-success {
            background: #d4edda;
            border: 2px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background: #f8d7da;
            border: 2px solid #f5c6cb;
            color: #721c24;
        }
        
        .alert-info {
            background: #d1ecf1;
            border: 2px solid #bee5eb;
            color: #0c5460;
        }
        
        .hints-box {
            background: linear-gradient(135deg, #fff3cd 0%, #fff8e1 100%);
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #ffc107;
            margin-top: 30px;
        }
        
        .hints-box h3 {
            color: #856404;
            margin-bottom: 15px;
        }
        
        .hints-box ul {
            margin-left: 20px;
            color: #856404;
        }
        
        .hints-box li {
            margin: 10px 0;
            line-height: 1.6;
        }
        
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }
        
        .back-btn:hover {
            background: #5a6268;
        }
        
        .flag-hint {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="../" class="back-btn">← กลับหน้าหลัก</a>
            <h1>🔥 Lab 2: Intermediate Union-based SQL Injection</h1>
            <div class="level-badge">
                ⭐⭐ ระดับ: ปานกลาง | Flag: 1 ข้อ 
                <span class="waf-indicator">🛡️ WAF Protected</span>
            </div>
        </div>
        
        <div class="content">
            <div class="search-box">
                <h2>🔍 Customer Lookup System</h2>
                <p style="color: #666; margin-bottom: 20px;">ค้นหาข้อมูลลูกค้าด้วย Customer ID (1-4)</p>
                
                <form method="GET" class="input-group">
                    <input type="text" name="id" placeholder="กรอก Customer ID เช่น 1, 2, 3, 4" 
                           value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                    <button type="submit">🔎 ค้นหา</button>
                </form>
            </div>

            <?php
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                
                // ตรวจสอบ WAF
                if(!basic_waf($id)) {
                    echo '<div class="alert alert-danger">';
                    echo '🚫 <strong>Access Denied!</strong> Malicious input detected by WAF.<br>';
                    echo '<small>Your request has been blocked and logged.</small>';
                    echo '</div>';
                } else {
                    $conn = new mysqli("mysql", "webapp2", "webapp2secure", "lab2_sqli");
                    
                    if ($conn->connect_error) {
                        echo '<div class="alert alert-danger">❌ Database connection failed</div>';
                    } else {
                        // Vulnerable SQL query
                        $query = "SELECT id, name, email, phone FROM customers WHERE id = $id";
                        $result = $conn->query($query);
                        
                        if($result && $result->num_rows > 0) {
                            echo '<div class="alert alert-success">✅ พบข้อมูลลูกค้า!</div>';
                            echo '<table>';
                            echo '<tr><th>ID</th><th>ชื่อ</th><th>อีเมล</th><th>เบอร์โทร</th></tr>';
                            
                            while($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>'.htmlspecialchars($row['id']).'</td>';
                                echo '<td>'.htmlspecialchars($row['name']).'</td>';
                                echo '<td>'.htmlspecialchars($row['email']).'</td>';
                                echo '<td>'.htmlspecialchars($row['phone']).'</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                        } else {
                            echo '<div class="alert alert-info">ℹ️ ไม่พบข้อมูลลูกค้า</div>';
                        }
                        
                        $conn->close();
                    }
                }
            }
            ?>
            
            <div class="hints-box">
                <h3>💡 คำใบ้สำหรับ Lab 2:</h3>
                <ul>
                    <li><strong>WAF Bypass:</strong> ระบบมี WAF ที่จะ block คำสำคัญ เช่น UNION, SELECT, WHERE, FROM</li>
                    <li><strong>เทคนิค:</strong> ลองใช้ Case Variation เช่น <code>UnIoN</code>, <code>SeLeCt</code></li>
                    <li><strong>เทคนิค:</strong> ลองใช้ Comment Injection เช่น <code>UN/**/ION</code></li>
                    <li><strong>Database:</strong> มี 3 ตาราง - customers, admin_users, secret_data</li>
                    <li><strong>Target:</strong> Flag อยู่ในตาราง <code>secret_data</code> คอลัมน์ <code>secret_value</code></li>
                    <li><strong>Columns:</strong> ตาราง customers มี 4 columns (id, name, email, phone)</li>
                </ul>
                
                <div class="flag-hint">
                    🏁 เป้าหมาย: Bypass WAF และดึง flag จาก secret_data table
                </div>
            </div>
        </div>
    </div>
</body>
</html>
