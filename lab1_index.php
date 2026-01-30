<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1 - Basic Union SQL Injection</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
            border-color: #11998e;
        }
        
        button {
            padding: 15px 40px;
            font-size: 16px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(17, 153, 142, 0.4);
        }
        
        .results-section {
            margin-top: 30px;
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
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="../" class="back-btn">← กลับหน้าหลัก</a>
            <h1>🎯 Lab 1: Basic Union-based SQL Injection</h1>
            <div class="level-badge">⭐ ระดับ: ง่าย | Flag: 1 ข้อ</div>
        </div>
        
        <div class="content">
            <div class="search-box">
                <h2>🔍 Product Search System</h2>
                <p style="color: #666; margin-bottom: 20px;">ค้นหาสินค้าด้วย Product ID (1-4)</p>
                
                <form method="GET" class="input-group">
                    <input type="text" name="id" placeholder="กรอก Product ID เช่น 1, 2, 3, 4" 
                           value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                    <button type="submit">🔎 ค้นหา</button>
                </form>
            </div>

            <?php
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                
                $conn = new mysqli("mysql", "webapp", "webapp123", "lab1_sqli");
                
                if ($conn->connect_error) {
                    echo '<div class="alert alert-danger">❌ Database connection failed</div>';
                } else {
                    // Vulnerable SQL query - ไม่มีการป้องกัน
                    $query = "SELECT id, name, price, description FROM products WHERE id = $id";
                    $result = $conn->query($query);
                    
                    if($result && $result->num_rows > 0) {
                        echo '<div class="results-section">';
                        echo '<div class="alert alert-success">✅ พบข้อมูลสินค้า!</div>';
                        echo '<table>';
                        echo '<tr><th>ID</th><th>ชื่อสินค้า</th><th>ราคา (บาท)</th><th>รายละเอียด</th></tr>';
                        
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>'.htmlspecialchars($row['id']).'</td>';
                            echo '<td>'.htmlspecialchars($row['name']).'</td>';
                            echo '<td>'.number_format($row['price'], 2).'</td>';
                            echo '<td>'.htmlspecialchars($row['description']).'</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-danger">❌ ไม่พบสินค้าที่ค้นหา</div>';
                    }
                    
                    $conn->close();
                }
            }
            ?>
            
            <div class="hints-box">
                <h3>💡 คำใบ้สำหรับ Lab 1:</h3>
                <ul>
                    <li><strong>Step 1:</strong> ทดสอบว่ามีช่องโหว่ SQL Injection หรือไม่ (ลองใส่ <code>1'</code>)</li>
                    <li><strong>Step 2:</strong> หาจำนวน columns ด้วย <code>ORDER BY</code></li>
                    <li><strong>Step 3:</strong> ใช้ <code>UNION SELECT</code> เพื่อหา position ที่แสดงผล</li>
                    <li><strong>Step 4:</strong> ดึงชื่อ database และ table names</li>
                    <li><strong>Step 5:</strong> ดึงชื่อ columns จากตาราง users</li>
                    <li><strong>Step 6:</strong> ดึงข้อมูลจากตาราง users เพื่อหา flag</li>
                </ul>
                
                <div class="flag-hint">
                    🏁 Flag อยู่ในตาราง 'users' คอลัมน์ 'password' ของ user 'flaguser'
                </div>
            </div>
        </div>
    </div>
</body>
</html>
