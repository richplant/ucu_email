<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
Mustache_Autoloader::register();

$msg = "Dear Prof. Nolan,

I am a student at Edinburgh Napier. I am concerned by the disruption from strike action, but want to emphasise my support for staff.

By doubling down on pay offers for staff that are well below inflation, you are threatening the future of the sector that, as employers, you are meant to be custodians of.

Across the UK, academic staff are working on average more than two days unpaid each week to get all their work done, because workloads are so high. Staff on fractional contracts can be working 2-3 times the hours that they are paid for.

This issue can also be clearly seen at ENU. You recently reported on the latest staff survey results, which found that “colleagues are feeling the impact of workload on their wellbeing”. These workloads affect staff mental health which in turn impacts on student learning conditions. Your latest offer will do next to nothing to redress years of cuts and degraded living standards, never mind support staff through the current crisis.

Equally, while Edinburgh Napier University made a local offer to EIS and UNISON, which those unions voted to accept, it continues to refuse to engage with or recognise the UCU is the largest trade union for higher education staff in Scotland. This cannot go on. 

I urge you, in the next round of pay negotiations, put an end to disruption by making a serious offer to university staff who are striking to defend their livelihoods and protect education. 

Ask UCEA to agree to staff demands on equality, job security, manageable workloads and fair pay: nationally-agreed action using an intersectional approach, to close the gender, ethnic and disability pay gaps; an agreed framework to eliminate precarious employment practices by universities; nationally agreed action to address excessive workloads and unpaid work, and the impact on workforce stress and ill-health – as well as a fair deal on pay, which addresses years of pay erosion.";

$intro = "One way to show support for striking staff at ENU is to let our Vice Chancellor know you support them. Sign and personalise the email below and click Submit to send it to Edinburgh Napier University’s Principal and Vice Chancellor, Prof Andrea Nolan at principal@napier.ac.uk";

$success = "";

if (!empty($_POST)) {
    
    $name = $_POST['name'];
    $email = $_POST['emailAddress'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $message = $message . "

    Yours sincerely,
    {$name}
    {$email}";

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['MAIL_U'];
    $mail->Password = $_ENV['MAIL_P'];
    $mail->setFrom('ucunapier@gmail.com');
    $mail->addReplyTo($email, $name);
    $mail->addAddress($_ENV['TO_A']);
    $mail->Subject = $subject;
    $mail->Body = $message;

    if (!$mail->send()) {
        $success = false;
    } else {
        $success = true;
    }
 };

$mustache = new Mustache_Engine(array(
    'template_class_prefix' => '__MyTemplates_',
    'cache' => dirname(__FILE__).'/tmp/cache/mustache',
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__)),
    'escape' => function($value) {
        return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
    },
    'charset' => 'ISO-8859-1',
    'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
    'strict_callables' => true,
));

$tpl = $mustache->loadTemplate('form'); 
echo $tpl->render(array(
    'msg_content' => $msg,
    'intro_content' => $intro,
    'success' => $success,
));