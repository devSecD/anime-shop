<?php
namespace Controllers\Admin\Setting;

use Core\Controller;
use App\Helpers\ValidationHelper;
use Models\Setting\SettingRepository;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ArrayHelper;
use App\Middleware\AdminMiddleware;
class IndexController extends Controller
{
    private $db;
    private AdminMiddleware $auth;
    public function __construct() 
    {
        $this->db = $this->loadDB();
        $this->auth = new AdminMiddleware();
    }
    public function index()
    {
        $this->auth->handle();

        $repo = new SettingRepository($this->db);
        $repo->initializeIfEmpty(); // Inicializa valores si tabla vacía

        $settings = $repo->getSettings();

        $filteredSettings = ArrayHelper::excludeByKeyValues($settings, 'key', 'logo_path');

        $settingAssoc = ArrayHelper::toAssocArray($settings, 'key', 'value');

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';

        $assets = ['form'];
        $title = 'Modificar configuraciones';
        $page = 'updateConfiguration';
        extract(compact('title', 'page', 'assets'));
        include __DIR__ . '/../../../view/admin/settings/index.php';
    }

    public function update()
    {
        $this->auth->handle();

        ValidationHelper::rejectIfNotPost();

        $settings = $_POST['settings'] ?? [];
        $errors = [];

        foreach ($settings as $key => $value) {
            $error = ValidationHelper::validateSetting($key, $value);
            if ($error) {
                $errors[$key] = $error;
            }
        }

        // Obtener logo actual desde DB antes de cambios
        $repo = new SettingRepository($this->db);
        $currentLogo = $repo->getSettingValue('logo_path'); // Método que retorna el valor actual de logo_path

        // Validar y procesar archivo logo si se subió
        if (isset($_FILES['logo_path']) && $_FILES['logo_path']['error'] !== UPLOAD_ERR_NO_FILE) {
            $fileError = ValidationHelper::validateImageUpload($_FILES['logo_path'], 'logo');
            if ($fileError) {
                $errors['logo_path'] = $fileError;
            } else {
                // Ruta absoluta de carpeta donde guardar el logo
                $uploadDir = __DIR__ . '/../../../../public/assets/images/logo/';
                // /anime-shop/public/assets/images/logo
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $extension = strtolower(pathinfo($_FILES['logo_path']['name'], PATHINFO_EXTENSION));
                $newFileName = 'logo_' . date('YmdHis') . '.' . $extension;
                $destination = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['logo_path']['tmp_name'], $destination)) {
        
                    // 🔹 Borrar logo anterior si existe y no es el default
                    if (!empty($currentLogo) && file_exists($uploadDir . $currentLogo) && $currentLogo !== 'default_logo.png') {
                        @unlink($uploadDir . $currentLogo);
                    }

                    $settings['logo_path'] = $newFileName;
                } else {
                    $errors['logo_path'] = "Error al guardar el archivo del logo.";
                }
            }
        }

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => StringHelper::implodeArray($errors, "\n"),
                'errors' => $errors
            ]);
        }

        $repo = new SettingRepository($this->db);
        foreach ($settings as $key => $value) {
            $repo->updateSetting($key, $value);
        }

        ResponseHelper::jsonResponse([
            'success' => true,
            'message' => '¡Configuracion actualizada exitosamente!',
            'redirect' => '/anime-shop/public/admin/setting'
        ]);
    }
}
