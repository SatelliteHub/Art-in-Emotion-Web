<?php

global $errorStrings;
global $errors;

// CONFIG
$successPage = "success.html"; 
$errorPage = "error.html"; 
$recipient = "artinemotion@proton.me"; 
$subject = "artinemotion - Contact"; 
$from = ""; 
$name = ""; 

$useRecipientList = "0"; 
$recipientList = array();
$allowsOtherDomains = TRUE;

$input_vars = array(
    'Name' => array('title' => 'Name', 'required' => '1', 'type' => 'firstName'),
    'Email' => array('title' => 'Email', 'required' => '1', 'type' => 'from', 'filter' => 'email'),
    'Message' => array('title' => 'Message', 'required' => '1')
);

$errorStrings = array(
    0 => 'Undefined error',
    1 => 'No form submitted',
    2 => 'Invalid E-Mail address',
    3 => 'E-Mail could not be delivered',
    4 => 'sendForm85973',
);

function appendError($errorNum, $errorString = NULL) {
    global $errorStrings, $errors, $customErrorNum;
    if (!$errors) $errors = array();
    if (!$customErrorNum) $customErrorNum = 0;

    if ($errorNum > 0 && array_key_exists($errorNum, $errorStrings))
        $message = $errorStrings[$errorNum];
    elseif ($errorString)
        $message = $errorString;
    else
        $message = $errorStrings[0];

    if ($errorNum == 0) {
        $errors["c$customErrorNum"] = $message;
        $customErrorNum++;
    } else {
        $errors[$errorNum] = $message;
    }
}

// PROCESSING
$input_type = INPUT_POST;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_type = INPUT_POST;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $input_type = INPUT_GET;
} else {
    appendError(1);
}

// Check honeypot field
if (!empty($_POST['honeypot'])) {
    appendError(0, "Bot detected!"); // Add an error if honeypot is filled
}

$safeMode = ini_get('safe_mode');
$safeMode = ($safeMode == 'On' || $safeMode == true);

$recipientId = 0;

if (($input_type == INPUT_POST && !empty($_POST['sendForm85973'])) ||
    ($input_type == INPUT_GET && !empty($_GET['sendForm85973']))) {
    appendError(4);
}

if (!$errors) {
    $firstName = false;
    $surname = false;
    $parameters = "";

    $emailBody = 'Website form filled out\n';
    foreach ($input_vars as $key => $var) {
        $field = NULL;
        if (filter_has_var($input_type, $key) && ($input_type == INPUT_POST ? !empty($_POST[$key]) : !empty($_GET[$key]))) {
            if (array_key_exists('filter', $var)) {
                switch ($var['filter']) {
                    case 'email':
                        $sanitized = filter_input($input_type, $key, FILTER_SANITIZE_EMAIL);
                        if (filter_input($input_type, $key, FILTER_VALIDATE_EMAIL)) {
                            $field = $sanitized;
                            if ($var['type'] == 'recipient') {
                                $recipient = $field;
                                $field = '';
                            } elseif ($var['type'] == 'from' && $allowsOtherDomains) {
                                $from = $field;
                            }
                        } else {
                            appendError(2);
                        }
                        break;
                    default:
                        $field = filter_input($input_type, $key, FILTER_SANITIZE_MAGIC_QUOTES);
                }
            } else {
                $field = filter_input($input_type, $key, FILTER_SANITIZE_MAGIC_QUOTES);
            }

            if ($field && array_key_exists('type', $var)) {
                if ($var['type'] == 'firstName') {
                    $firstName = $field;
                }
            }
        } elseif (array_key_exists('required', $var) && $var['required']) {
            appendError(0, "$key is a required field");
        }

        if ($field) {
            $emailBody .= $var['title'] . ": $field\n";
        }
    }
}

// Sending
if (!$errors) {
    if ($firstName) {
        $name = $firstName;
    }

    $headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
    if ($name) {
        $fromHeader = "From: \"$name\" <$from>\r\n";
    } else {
        $fromHeader = "From: $from\r\n";
    }

    if ($safeMode) {
        $mailSuccess = mail($recipient, $subject, "$emailBody", $headers . $fromHeader);
    } else {
        $mailSuccess = mail($recipient, $subject, "$emailBody", $headers . $fromHeader);
    }

    if (!$mailSuccess) {
        appendError(3);
    }
}

// Finishing up
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

if ((substr($errorPage, 0, 7) != 'http://') && (substr($errorPage, 0, 8) != 'https://')) {
    if (strpos($errorPage, "/") === 0) {
        $errorPage = "http://$host$errorPage";
    } else {
        $errorPage = "http://$host$uri/$errorPage";
    }
}

if ((substr($successPage, 0, 7) != 'http://') && (substr($successPage, 0, 8) != 'https://')) {
    if (strpos($successPage, "/") === 0) {
        $successPage = "http://$host$successPage";
    } else {
        $successPage = "http://$host$uri/$successPage";
    }
}

if ($errors && !array_key_exists(4, $errors)) {
    $errorsUrlString = urlencode(implode(",", $errors));
    header("Location: $errorPage?$errorsUrlString");
} else {
    header("Location: $successPage");
}
