<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyên đề Ma Trận</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        h1 { text-align: center; color: #667eea; margin-bottom: 30px; }
        h2 { color: #764ba2; margin-top: 30px; margin-bottom: 15px; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="number"], input[type="text"] { width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 16px; }
        input[type="number"]:focus, input[type="text"]:focus { border-color: #667eea; outline: none; }
        button { background: #667eea; color: white; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px; margin-top: 10px; }
        button:hover { background: #764ba2; }
        .matrix-input { display: inline-block; margin: 5px; width: 60px; padding: 8px; text-align: center; border: 2px solid #667eea; border-radius: 5px; }
        .matrix-display { margin: 20px 0; padding: 20px; background: #f8f9fa; border-radius: 5px; border-left: 4px solid #667eea; }
        table { border-collapse: collapse; margin: 10px auto; }
        td { border: 2px solid #667eea; padding: 10px 15px; text-align: center; min-width: 50px; background: white; }
        .result { background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #dc3545; color: #721c24; }
        .success { background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #17a2b8; color: #0c5460; }
        #matrixInputs, #matrix2Inputs { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔢 CHUYÊN ĐỀ MA TRẬN 🔢</h1>

<?php
// ==================== CÁC HÀM XỬ LÝ MA TRẬN ====================

/**
 * Hàm tính định thức của ma trận vuông
 * Sử dụng phương pháp khai triển Laplace theo hàng đầu tiên
 * @param array $matrix - Ma trận cần tính định thức
 * @return float - Giá trị định thức
 */
function tinhDinhThuc($matrix) {
    $n = count($matrix);
    
    // Trường hợp cơ sở: ma trận 1x1
    if ($n == 1) {
        return $matrix[0][0];
    }
    
    // Trường hợp ma trận 2x2
    if ($n == 2) {
        return $matrix[0][0] * $matrix[1][1] - $matrix[0][1] * $matrix[1][0];
    }
    
    // Trường hợp ma trận nxn (n >= 3): dùng khai triển Laplace
    $det = 0;
    for ($j = 0; $j < $n; $j++) {
        // Tạo ma trận con bỏ đi hàng 0 và cột j
        $subMatrix = [];
        for ($i = 1; $i < $n; $i++) {
            $row = [];
            for ($k = 0; $k < $n; $k++) {
                if ($k != $j) {
                    $row[] = $matrix[$i][$k];
                }
            }
            $subMatrix[] = $row;
        }
        // Tính định thức theo công thức: det = Σ((-1)^j * a[0][j] * det(subMatrix))
        $det += pow(-1, $j) * $matrix[0][$j] * tinhDinhThuc($subMatrix);
    }
    
    return $det;
}

/**
 * Hàm sắp xếp ma trận giảm dần
 * Chuyển ma trận thành mảng 1 chiều, sắp xếp, rồi chuyển lại về ma trận
 * @param array $matrix - Ma trận cần sắp xếp
 * @return array - Ma trận đã được sắp xếp
 */
function sapXepGiamDan($matrix) {
    $arr = [];
    // Chuyển ma trận 2 chiều thành mảng 1 chiều
    foreach ($matrix as $row) {
        foreach ($row as $value) {
            $arr[] = $value;
        }
    }
    
    // Sắp xếp giảm dần
    rsort($arr);
    
    // Chuyển lại thành ma trận 2 chiều
    $result = [];
    $index = 0;
    for ($i = 0; $i < count($matrix); $i++) {
        for ($j = 0; $j < count($matrix[0]); $j++) {
            $result[$i][$j] = $arr[$index++];
        }
    }
    
    return $result;
}

/**
 * Hàm tính tổng hai ma trận
 * @param array $m1 - Ma trận thứ nhất
 * @param array $m2 - Ma trận thứ hai
 * @return array|string - Ma trận tổng hoặc thông báo lỗi
 */
function tinhTong($m1, $m2) {
    // Kiểm tra kích thước
    if (count($m1) != count($m2) || count($m1[0]) != count($m2[0])) {
        return "Lỗi: Hai ma trận phải có cùng kích thước để tính tổng!";
    }
    
    $result = [];
    for ($i = 0; $i < count($m1); $i++) {
        for ($j = 0; $j < count($m1[0]); $j++) {
            $result[$i][$j] = $m1[$i][$j] + $m2[$i][$j];
        }
    }
    return $result;
}

/**
 * Hàm tính hiệu hai ma trận
 * @param array $m1 - Ma trận thứ nhất
 * @param array $m2 - Ma trận thứ hai
 * @return array|string - Ma trận hiệu hoặc thông báo lỗi
 */
function tinhHieu($m1, $m2) {
    // Kiểm tra kích thước
    if (count($m1) != count($m2) || count($m1[0]) != count($m2[0])) {
        return "Lỗi: Hai ma trận phải có cùng kích thước để tính hiệu!";
    }
    
    $result = [];
    for ($i = 0; $i < count($m1); $i++) {
        for ($j = 0; $j < count($m1[0]); $j++) {
            $result[$i][$j] = $m1[$i][$j] - $m2[$i][$j];
        }
    }
    return $result;
}

/**
 * Hàm tính tích hai ma trận
 * @param array $m1 - Ma trận thứ nhất (kích thước m x n)
 * @param array $m2 - Ma trận thứ hai (kích thước n x p)
 * @return array|string - Ma trận tích hoặc thông báo lỗi
 */
function tinhTich($m1, $m2) {
    // Kiểm tra điều kiện: số cột của m1 phải bằng số hàng của m2
    if (count($m1[0]) != count($m2)) {
        return "Lỗi: Số cột của ma trận 1 phải bằng số hàng của ma trận 2 để tính tích!";
    }
    
    $result = [];
    $m = count($m1);      // Số hàng của m1
    $n = count($m1[0]);   // Số cột của m1 = số hàng của m2
    $p = count($m2[0]);   // Số cột của m2
    
    // Tính tích ma trận theo công thức: C[i][j] = Σ(A[i][k] * B[k][j])
    for ($i = 0; $i < $m; $i++) {
        for ($j = 0; $j < $p; $j++) {
            $result[$i][$j] = 0;
            for ($k = 0; $k < $n; $k++) {
                $result[$i][$j] += $m1[$i][$k] * $m2[$k][$j];
            }
        }
    }
    
    return $result;
}

/**
 * Hàm kiểm tra ma trận có phải là ma phương (Magic Square) không
 * Ma phương: tổng các phần tử trên mỗi hàng, mỗi cột, đường chéo chính và phụ đều bằng nhau
 * @param array $matrix - Ma trận cần kiểm tra
 * @return bool - true nếu là ma phương, false nếu không
 */
function kiemTraMaPhuong($matrix) {
    $n = count($matrix);
    
    // Chỉ kiểm tra ma trận vuông
    if ($n != count($matrix[0])) {
        return false;
    }
    
    // Tính tổng hàng đầu tiên làm chuẩn
    $magicSum = 0;
    for ($j = 0; $j < $n; $j++) {
        $magicSum += $matrix[0][$j];
    }
    
    // Kiểm tra tổng các hàng
    for ($i = 1; $i < $n; $i++) {
        $rowSum = 0;
        for ($j = 0; $j < $n; $j++) {
            $rowSum += $matrix[$i][$j];
        }
        if ($rowSum != $magicSum) {
            return false;
        }
    }
    
    // Kiểm tra tổng các cột
    for ($j = 0; $j < $n; $j++) {
        $colSum = 0;
        for ($i = 0; $i < $n; $i++) {
            $colSum += $matrix[$i][$j];
        }
        if ($colSum != $magicSum) {
            return false;
        }
    }
    
    // Kiểm tra đường chéo chính (từ trên trái xuống dưới phải)
    $diagSum1 = 0;
    for ($i = 0; $i < $n; $i++) {
        $diagSum1 += $matrix[$i][$i];
    }
    if ($diagSum1 != $magicSum) {
        return false;
    }
    
    // Kiểm tra đường chéo phụ (từ trên phải xuống dưới trái)
    $diagSum2 = 0;
    for ($i = 0; $i < $n; $i++) {
        $diagSum2 += $matrix[$i][$n - 1 - $i];
    }
    if ($diagSum2 != $magicSum) {
        return false;
    }
    
    return true;
}

/**
 * Hàm tạo ma phương bậc n
 * Sử dụng thuật toán Siamese (De la Loubere) cho ma phương bậc lẻ
 * @param int $n - Bậc của ma phương (phải là số lẻ)
 * @return array|string - Ma phương hoặc thông báo lỗi
 */
function taoMaPhuong($n) {
    // Chỉ hỗ trợ ma phương bậc lẻ với thuật toán Siamese
    if ($n % 2 == 0) {
        return "Lưu ý: Thuật toán này chỉ hỗ trợ ma phương bậc lẻ (3, 5, 7, 9...)";
    }
    
    // Khởi tạo ma trận với giá trị 0
    $matrix = array_fill(0, $n, array_fill(0, $n, 0));
    
    // Bắt đầu từ giữa hàng đầu tiên
    $i = 0;
    $j = floor($n / 2);
    
    // Điền các số từ 1 đến n*n
    for ($num = 1; $num <= $n * $n; $num++) {
        $matrix[$i][$j] = $num;
        
        // Tính vị trí tiếp theo: lên 1 hàng, sang phải 1 cột
        $newI = ($i - 1 + $n) % $n;  // Quay vòng nếu vượt biên
        $newJ = ($j + 1) % $n;
        
        // Nếu ô đó đã có số, thì đi xuống 1 hàng từ vị trí hiện tại
        if ($matrix[$newI][$newJ] != 0) {
            $i = ($i + 1) % $n;
        } else {
            $i = $newI;
            $j = $newJ;
        }
    }
    
    return $matrix;
}

/**
 * Hàm hiển thị ma trận dưới dạng bảng HTML
 * @param array $matrix - Ma trận cần hiển thị
 * @return string - Chuỗi HTML
 */
function hienThiMaTran($matrix) {
    $html = "<table>";
    foreach ($matrix as $row) {
        $html .= "<tr>";
        foreach ($row as $value) {
            $html .= "<td>" . $value . "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}

/**
 * Hàm kiểm tra dữ liệu nhập vào có hợp lệ không
 * @param string $value - Giá trị cần kiểm tra
 * @return bool - true nếu hợp lệ, false nếu không
 */
function kiemTraHopLe($value) {
    return is_numeric($value) && $value !== '';
}
?>

        <!-- PHẦN 1: NHẬP VÀ XỬ LÝ MỘT MA TRẬN -->
        <h2>📝 Phần 1: Nhập và xử lý một ma trận</h2>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Số hàng (m):</label>
                <input type="number" name="rows" id="rows" min="1" max="10" required>
            </div>
            <div class="form-group">
                <label>Số cột (n):</label>
                <input type="number" name="cols" id="cols" min="1" max="10" required>
            </div>
            <button type="button" onclick="taoONhap()">Tạo ô nhập liệu</button>
        </form>

        <div id="matrixInputs"></div>

        <?php
        // XỬ LÝ PHẦN 1: MỘT MA TRẬN
        if (isset($_POST['matrix']) && isset($_POST['rows']) && isset($_POST['cols'])) {
            $m = intval($_POST['rows']);
            $n = intval($_POST['cols']);
            $matrix = [];
            $valid = true;
            
            // Đọc dữ liệu từ form và kiểm tra tính hợp lệ
            for ($i = 0; $i < $m; $i++) {
                for ($j = 0; $j < $n; $j++) {
                    $value = $_POST['matrix'][$i][$j];
                    if (!kiemTraHopLe($value)) {
                        echo "<div class='error'>❌ Lỗi: Phần tử tại vị trí [" . ($i+1) . "][" . ($j+1) . "] không hợp lệ! Vui lòng nhập số.</div>";
                        $valid = false;
                        break 2;
                    }
                    $matrix[$i][$j] = floatval($value);
                }
            }
            
            if ($valid) {
                echo "<div class='matrix-display'>";
                echo "<h3>Ma trận đã nhập:</h3>";
                echo hienThiMaTran($matrix);
                echo "</div>";
                
                // Tính định thức nếu là ma trận vuông
                if ($m == $n) {
                    $det = tinhDinhThuc($matrix);
                    echo "<div class='result'>";
                    echo "<strong>✅ Định thức của ma trận:</strong> " . $det;
                    echo "</div>";
                } else {
                    echo "<div class='error'>⚠️ Ma trận không vuông nên không tính được định thức.</div>";
                }
                
                // Sắp xếp ma trận giảm dần
                $sortedMatrix = sapXepGiamDan($matrix);
                echo "<div class='matrix-display'>";
                echo "<h3>Ma trận sau khi sắp xếp giảm dần:</h3>";
                echo hienThiMaTran($sortedMatrix);
                echo "</div>";
                
                // Kiểm tra ma phương
                if (kiemTraMaPhuong($matrix)) {
                    echo "<div class='success'>🌟 Đây là một MA PHƯƠNG! Tổng các hàng, cột và đường chéo đều bằng nhau.</div>";
                } else {
                    echo "<div class='error'>❌ Đây KHÔNG phải là ma phương.</div>";
                }
            }
        }
        ?>

        <!-- PHẦN 2: NHẬP VÀ TÍNH TOÁN HAI MA TRẬN -->
        <h2>🔢 Phần 2: Tính toán với hai ma trận</h2>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Số hàng ma trận 1:</label>
                <input type="number" name="rows1" id="rows1" min="1" max="10" required>
            </div>
            <div class="form-group">
                <label>Số cột ma trận 1:</label>
                <input type="number" name="cols1" id="cols1" min="1" max="10" required>
            </div>
            <div class="form-group">
                <label>Số hàng ma trận 2:</label>
                <input type="number" name="rows2" id="rows2" min="1" max="10" required>
            </div>
            <div class="form-group">
                <label>Số cột ma trận 2:</label>
                <input type="number" name="cols2" id="cols2" min="1" max="10" required>
            </div>
            <button type="button" onclick="taoONhap2MaTran()">Tạo ô nhập liệu cho 2 ma trận</button>
        </form>

        <div id="matrix2Inputs"></div>

        <?php
        // XỬ LÝ PHẦN 2: HAI MA TRẬN
        if (isset($_POST['matrix1']) && isset($_POST['matrix2'])) {
            $m1 = intval($_POST['rows1']);
            $n1 = intval($_POST['cols1']);
            $m2 = intval($_POST['rows2']);
            $n2 = intval($_POST['cols2']);
            
            $matrix1 = [];
            $matrix2 = [];
            $valid = true;
            
            // Đọc ma trận 1
            for ($i = 0; $i < $m1; $i++) {
                for ($j = 0; $j < $n1; $j++) {
                    $value = $_POST['matrix1'][$i][$j];
                    if (!kiemTraHopLe($value)) {
                        echo "<div class='error'>❌ Lỗi: Ma trận 1 có dữ liệu không hợp lệ!</div>";
                        $valid = false;
                        break 2;
                    }
                    $matrix1[$i][$j] = floatval($value);
                }
            }
            
            // Đọc ma trận 2
            for ($i = 0; $i < $m2; $i++) {
                for ($j = 0; $j < $n2; $j++) {
                    $value = $_POST['matrix2'][$i][$j];
                    if (!kiemTraHopLe($value)) {
                        echo "<div class='error'>❌ Lỗi: Ma trận 2 có dữ liệu không hợp lệ!</div>";
                        $valid = false;
                        break 2;
                    }
                    $matrix2[$i][$j] = floatval($value);
                }
            }
            
            if ($valid) {
                echo "<div class='matrix-display'>";
                echo "<h3>Ma trận 1:</h3>";
                echo hienThiMaTran($matrix1);
                echo "<h3>Ma trận 2:</h3>";
                echo hienThiMaTran($matrix2);
                echo "</div>";
                
                // Tính tổng
                $tong = tinhTong($matrix1, $matrix2);
                if (is_array($tong)) {
                    echo "<div class='matrix-display'>";
                    echo "<h3>Ma trận TỔNG (A + B):</h3>";
                    echo hienThiMaTran($tong);
                    echo "</div>";
                } else {
                    echo "<div class='error'>" . $tong . "</div>";
                }
                
                // Tính hiệu
                $hieu = tinhHieu($matrix1, $matrix2);
                if (is_array($hieu)) {
                    echo "<div class='matrix-display'>";
                    echo "<h3>Ma trận HIỆU (A - B):</h3>";
                    echo hienThiMaTran($hieu);
                    echo "</div>";
                } else {
                    echo "<div class='error'>" . $hieu . "</div>";
                }
                
                // Tính tích
                $tich = tinhTich($matrix1, $matrix2);
                if (is_array($tich)) {
                    echo "<div class='matrix-display'>";
                    echo "<h3>Ma trận TÍCH (A × B):</h3>";
                    echo hienThiMaTran($tich);
                    echo "</div>";
                } else {
                    echo "<div class='error'>" . $tich . "</div>";
                }
            }
        }
        ?>

        <!-- PHẦN 3: TẠO MA PHƯƠNG -->
        <h2>✨ Phần 3: Tạo ma phương bậc n</h2>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Nhập bậc của ma phương (số lẻ từ 3 đến 11):</label>
                <input type="number" name="magic_n" min="3" max="11" step="2" required>
            </div>
            <button type="submit" name="create_magic">Tạo ma phương</button>
        </form>

        <?php
        // XỬ LÝ PHẦN 3: TẠO MA PHƯƠNG
        if (isset($_POST['create_magic']) && isset($_POST['magic_n'])) {
            $n = intval($_POST['magic_n']);
            
            if ($n % 2 == 0) {
                echo "<div class='error'>❌ Vui lòng nhập số lẻ (3, 5, 7, 9, 11...)</div>";
            } else {
                $magicSquare = taoMaPhuong($n);
                
                if (is_array($magicSquare)) {
                    echo "<div class='matrix-display'>";
                    echo "<h3>Ma phương bậc $n:</h3>";
                    echo hienThiMaTran($magicSquare);
                    echo "</div>";
                    
                    // Tính tổng ma phương
                    $magicSum = 0;
                    for ($j = 0; $j < $n; $j++) {
                        $magicSum += $magicSquare[0][$j];
                    }
                    
                    echo "<div class='success'>";
                    echo "🌟 <strong>Tổng ma phương:</strong> $magicSum<br>";
                    echo "📊 Tất cả các hàng, cột và đường chéo đều có tổng bằng $magicSum";
                    echo "</div>";
                    
                    // Xác nhận là ma phương
                    if (kiemTraMaPhuong($magicSquare)) {
                        echo "<div class='result'>✅ Xác nhận: Đây là một MA PHƯƠNG hợp lệ!</div>";
                    }
                } else {
                    echo "<div class='error'>" . $magicSquare . "</div>";
                }
            }
        }
        ?>

    </div>

    <script>
        // Hàm tạo ô nhập liệu cho 1 ma trận (Phần 1)
        function taoONhap() {
            const rows = document.getElementById('rows').value;
            const cols = document.getElementById('cols').value;
            
            if (rows < 1 || cols < 1 || rows > 10 || cols > 10) {
                alert('Vui lòng nhập số hàng và cột từ 1 đến 10!');
                return;
            }
            
            let html = '<form method="POST" action="">';
            html += '<input type="hidden" name="rows" value="' + rows + '">';
            html += '<input type="hidden" name="cols" value="' + cols + '">';
            html += '<h3>Nhập các phần tử của ma trận ' + rows + 'x' + cols + ':</h3>';
            html += '<div style="margin: 20px 0;">';
            
            for (let i = 0; i < rows; i++) {
                html += '<div style="margin: 10px 0;">';
                for (let j = 0; j < cols; j++) {
                    html += '<input type="text" class="matrix-input" name="matrix[' + i + '][' + j + ']" placeholder="[' + (i+1) + '][' + (j+1) + ']" required>';
                }
                html += '</div>';
            }
            
            html += '</div>';
            html += '<button type="submit">Xử lý ma trận</button>';
            html += '</form>';
            
            document.getElementById('matrixInputs').innerHTML = html;
        }

        // Hàm tạo ô nhập liệu cho 2 ma trận (Phần 2)
        function taoONhap2MaTran() {
            const rows1 = document.getElementById('rows1').value;
            const cols1 = document.getElementById('cols1').value;
            const rows2 = document.getElementById('rows2').value;
            const cols2 = document.getElementById('cols2').value;
            
            if (rows1 < 1 || cols1 < 1 || rows2 < 1 || cols2 < 1 || 
                rows1 > 10 || cols1 > 10 || rows2 > 10 || cols2 > 10) {
                alert('Vui lòng nhập số hàng và cột từ 1 đến 10!');
                return;
            }
            
            let html = '<form method="POST" action="">';
            html += '<input type="hidden" name="rows1" value="' + rows1 + '">';
            html += '<input type="hidden" name="cols1" value="' + cols1 + '">';
            html += '<input type="hidden" name="rows2" value="' + rows2 + '">';
            html += '<input type="hidden" name="cols2" value="' + cols2 + '">';
            
            // Ma trận 1
            html += '<h3>Nhập ma trận 1 (' + rows1 + 'x' + cols1 + '):</h3>';
            html += '<div style="margin: 20px 0;">';
            for (let i = 0; i < rows1; i++) {
                html += '<div style="margin: 10px 0;">';
                for (let j = 0; j < cols1; j++) {
                    html += '<input type="text" class="matrix-input" name="matrix1[' + i + '][' + j + ']" placeholder="[' + (i+1) + '][' + (j+1) + ']" required>';
                }
                html += '</div>';
            }
            html += '</div>';
            
            // Ma trận 2
            html += '<h3>Nhập ma trận 2 (' + rows2 + 'x' + cols2 + '):</h3>';
            html += '<div style="margin: 20px 0;">';
            for (let i = 0; i < rows2; i++) {
                html += '<div style="margin: 10px 0;">';
                for (let j = 0; j < cols2; j++) {
                    html += '<input type="text" class="matrix-input" name="matrix2[' + i + '][' + j + ']" placeholder="[' + (i+1) + '][' + (j+1) + ']" required>';
                }
                html += '</div>';
            }
            html += '</div>';
            
            html += '<button type="submit">Tính toán</button>';
            html += '</form>';
            
            document.getElementById('matrix2Inputs').innerHTML = html;
        }
    </script>
</body>
</html>
