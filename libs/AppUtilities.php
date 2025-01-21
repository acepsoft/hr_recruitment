<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AppUtilities {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getSelect($table, $columnName, $id, $default = '', $where = '', $onChangeFunction = '')
    {
        // Use Bootstrap class for select
        $content = '<select class="form-select" id="' . htmlspecialchars($id, ENT_QUOTES) . '" name="' . htmlspecialchars($id, ENT_QUOTES) . '"';
        if (!empty($onChangeFunction)) {
            $content .= ' onchange="' . htmlspecialchars($onChangeFunction, ENT_QUOTES) . '"';
        }
        $content .= '>';

        // Default option
        $content .= '<option value="0"' . (empty($default) ? ' selected' : '') . '>Select</option>';

        // Prepare and execute query
        $query = "SELECT id, " . $columnName . " FROM `" . $table . "` WHERE 1 " . $where;
        $result = $this->db->executeQuery($query);

        // Generate options
        foreach ($result as $row) {
            $isSelected = ($default == $row['id']) ? ' selected' : '';
            $content .= '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES) . '"' . $isSelected . '>' . htmlspecialchars_decode($row[$columnName], ENT_QUOTES) . '</option>';
        }

        $content .= '</select>';
        return $content;
    }

    public function getSelectButtons($table, $columna, $id, $default = '', $where = '', $function = '') {
        $contenido = '<div class="text-center">';
        $query = "SELECT a.id, a." . $columna . " FROM `" . htmlspecialchars($table, ENT_QUOTES) . "` a WHERE 1 " . $where;
        $result = $this->db->executeQuery($query);

        foreach ($result as $row) {
            $contenido .= '<label class="check"><input type="checkbox" ' . ($default == htmlspecialchars($row['id'], ENT_QUOTES) ? 'checked' : '') . ' class="select' . htmlspecialchars($id, ENT_QUOTES) . '" value="' . htmlspecialchars($default, ENT_QUOTES) . htmlspecialchars($row['id'], ENT_QUOTES) . '"> <span>' . htmlspecialchars($row[$columna], ENT_QUOTES) . '</span></label> ';
        }

        $contenido .= '</div>';
        return $contenido;
    }

    public function getMenu() {
        require "../libs/lang.php";
        $list = '';

        if ($_SESSION['admin'] == 1) { // admin
            $list .= '<ul style="margin-top:40px;margin-left:0;">';

            if ($_SESSION['ai_enabled'] == 2) {
                $list .= '<li ' . (isset($_GET['dash']) && isset($_GET['calendario']) ? 'class="active"' : '') . '>';
                $list .= '<a href="?dash&calendario"><i class="bi bi-calendar-week"></i> Calendar</a>';
                $list .= '</li>';
                $list .= '<li ' . (isset($_GET['settings']) ? 'class="active"' : '') . '>';
                $list .= '<a href="?settings"><i class="bi bi-gear"></i> Settings</a>';
                $list .= '</li>';
            }

            $list .= '</ul>';
        }

        return $list;
    }

    public function getFooter() {
        return '</body></html>';
    }

    public function passwordGenerate($length = 8, $minLowercases = 1, $minUppercases = 1, $minNumbers = 1, $minSpecials = 1) {
        $lowercases = 'abcdefghijklmnopqrstuvwxyz';
        $uppercases = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specials = '!#%&/(){}[]+-';

        $absolutes = '';
        if ($minLowercases && !is_bool($minLowercases)) $absolutes .= substr(str_shuffle(str_repeat($lowercases, $minLowercases)), 0, $minLowercases);
        if ($minUppercases && !is_bool($minUppercases)) $absolutes .= substr(str_shuffle(str_repeat($uppercases, $minUppercases)), 0, $minUppercases);
        if ($minNumbers && !is_bool($minNumbers)) $absolutes .= substr(str_shuffle(str_repeat($numbers, $minNumbers)), 0, $minNumbers);
        if ($minSpecials && !is_bool($minSpecials)) $absolutes .= substr(str_shuffle(str_repeat($specials, $minSpecials)), 0, $minSpecials);

        $remaining = $length - strlen($absolutes);
        $characters = '';
        if ($minLowercases !== false) $characters .= substr(str_shuffle(str_repeat($lowercases, $remaining)), 0, $remaining);
        if ($minUppercases !== false) $characters .= substr(str_shuffle(str_repeat($uppercases, $remaining)), 0, $remaining);
        if ($minNumbers !== false) $characters .= substr(str_shuffle(str_repeat($numbers, $remaining)), 0, $remaining);
        if ($minSpecials !== false) $characters .= substr(str_shuffle(str_repeat($specials, $remaining)), 0, $remaining);

        $password = str_shuffle($absolutes . substr($characters, 0, $remaining));
        return $password;
    }

    public function sendEmail($name, $email, $title, $body, $titulopdf, $pdf = '') {
        require_once $GLOBALS['path_var'] . '/httpdocs/libs/email_settings.php';

        $query = "SELECT a.*, b.name as estadotxt FROM empresa_settings a left join states b on b.id=a.estado where a.id = 1";
        $result2 = $this->db->consulta($query);
        $row_settings = mysqli_fetch_array($result2);

        $mail = new PHPMailer(true);
        $ecommerceSettings = new Ecommerce_settings();

        try {
            $mail->isSMTP();
            $mail->IsHTML(true);

            $mail->Host = $ecommerceSettings->getHost();
            $mail->SMTPAuth = $ecommerceSettings->isSMTPAuth();
            $mail->Username = $ecommerceSettings->getUsername();
            $mail->Password = $ecommerceSettings->getPassword();
            $mail->SMTPSecure = $ecommerceSettings->getSMTPSecure();
            $mail->Port = $ecommerceSettings->getPort();

            $mail->addReplyTo($row_settings['email'], $row_settings['nombre']);
            $mail->FromName = $ecommerceSettings->getFromName();
            $mail->From = $ecommerceSettings->getFrom();

            $mail->AddAddress($email, $name);

            if ($pdf != '') {
                $mail->addStringAttachment($pdf, $titulopdf . '.pdf', 'base64', 'application/pdf');
            }

            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $body;
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function htmlCode($text) {
        $search = array('“', '”', '’');
        $replace = array('"', '"', "'");
        $text = str_replace($search, $replace, $text);

        $search = array("<", ">");
        $replace = array("&lt;", "&gt;");
        $final = str_replace($search, $replace, $text);

        return htmlspecialchars($final, ENT_QUOTES);
    }
}
?>