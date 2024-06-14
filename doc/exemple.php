<?php
/**
 * SSO example
 */
namespace Dnx\Sso;

require_once __DIR__ . '/../src/Client.php';
require_once __DIR__ . '/../src/ServiceEnum.php';
require_once __DIR__ . '/../src/Response.php';

try {
    // Default data
    $data = [
        'email' => '',
        'loginToken' => '',
        'userIp' => $_SERVER['REMOTE_ADDR'],
        'country' => 'FR',
        'language' => 'fr',
        'model' => 'c0023',
        'service' => '',
        'tracker' => '',
    ];
    // You API token, ask your commercial contact for more information
    $apiToken = '';
    // Response from API
    $response = null;
    // Login token for further usage
    $loginToken = null;

    // Handle form submission
    if ($_POST['apiToken']) {
        // Prepare data input
        $apiToken = $_POST['apiToken'];
        $loginToken = $_POST['loginToken'] ?? null;
        // Create them in correct order for array destructuring
        $data = [
            'email' => $_POST['email'] ?? null,
        ];
        if ($loginToken) {
            $data['loginToken'] = $loginToken;
        }
        $data = array_merge($data, [
            'userIp' => $_SERVER['REMOTE_ADDR'],
            'model' => $_POST['model'] ?? null,
            'service' => $_POST['service'] ? ServiceEnum::from($_POST['service']) : null,
            'country' => strtoupper($_POST['country']) ?? null,
            'language' => $_POST['language'] ?? null,
            'tracker' => $_POST['tracker'] ?? null,
        ]);

        // Create basic http auth token if needed
        $header = [];
//        $httpUser = 'test';
//        $httpPass = 'test';
//        $header = ['Authorization: Basic ' . base64_encode($httpUser . ':' . $httpPass)];

        $client = new Client($apiToken, headers: $header);
        // Call login or register
        if ($loginToken) {
            $response = $client->login(...$data);
        } else {
            $response = $client->register(...$data);
            if ($response->isSuccess()) {
                // Retrieve login token for further use
                $loginToken = $response->getLoginToken();
                // Persist changes in database, it won't be displayed anymore
                // In any case the client will be redirected to a login page on failure.
            }
        }

        // You can now handle the response as you want.
        // Check $response->getStatusCode() for specific cases or just
        // $response->isSuccess() to know if it's a success or not based on http code.
        // $response->getRedirectUrl() contains the url to redirect the user to.
    }

    // Prepare form display
    $languages = [
        'EN',
        'FR',
        'NL',
        'IT',
        'ES',
        'DE',
        'PT',
        'FI',
        'EL',
        'DA',
        'SV',
        'NO',
        'TR',
        'SH',
    ];
} catch (\Throwable $e) {
    var_dump($e);
}
?>
<!DOCTYPE html>
<head>
<title>Example SSO</title>
</head>
<body>
<?php
if ($response) {
    ?>
    <div style="background: #ccc;">
        <h4>Data</h4>
        <?php
        echo '<p>HTTP Code ' . $response->getStatusCode() . ': ' . $response->getReason() . '</p>';
        if ($response->isSuccess()) {
        ?>
        <div>
            <a href="<?= $response->getRedirectUrl() ?>" target="_blank">
                <?= $response->getRedirectUrl() ?>
            </a>
        </div>
        <?php
        }
        ?>
        <div>
            <pre><?= $response->getData() ?></pre>
        </div>
    </div>
<?php
}
?>
    <form method="post">
        <div>
            <label for="apiToken">API token *</label>
            <input type="text" id="apiToken" name="apiToken" value="<?= $apiToken ?>"/>
        </div>
        <div>
            <label for="email">Email *</label>
            <input type="text" id="email" name="email" value="<?= $data['email'] ?>" />
        </div>
        <div>
            <label for="loginToken">Login token (optional)</label>
            <input type="text" id="loginToken" name="loginToken" value="<?= $loginToken ?>" />
        </div>
        <div>
            <label for="model">Model</label>
            <input type="text" id="model" name="model" value="<?= $data['model'] ?>" />
        </div>
        <div>
            <label for="service">Service</label>
            <select name="service" id="service">
                <option value="">** Select **</option>
                <option value="profile"<?= $data['service'] === 'profile' ? ' selected' : '' ?>>Profile</option>
                <option value="webcamsList"<?= $data['service'] === 'webcamsList' ? ' selected' : '' ?>>Webcams list</option>
            </select>
        </div>
        <div>
            <label for="country">Country</label>
            <input pattern="[A-Z]{2}" type="text" id="country" name="country" value="<?= $data['country'] ?>" />
        </div>
        <div>
            <label for="language">Language</label>
            <select name="language" id="language">
                <option value="">** Select **</option>
                <?php
                foreach ($languages as $l) {
                    echo '<option value="' . $l . '"' . ($data['language'] === $l ? ' selected' : '') . '>' . $l . '</option>';
                }
                ?>
            </select>
        </div>
        <div>
            <label for="tracker">Tracker</label>
            <input type="text" id="tracker" name="tracker" />
        </div>
        <div>
            <input type="submit" />
        </div>
    </form>
</body>