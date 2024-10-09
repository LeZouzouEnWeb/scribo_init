<?php
class ScriboInit
{
    private $plugin_file;

    public function __construct($plugin_file)
    {
        $this->plugin_file = $plugin_file;
    }

    /**
     * Récupère la valeur d'un en-tête spécifique du plugin.
     *
     * @param string $nameEntete Le nom de l'en-tête à récupérer.
     * @return string La valeur de l'en-tête ou une chaîne vide si non trouvé.
     */
    public function VarEntete(string $nameEntete): string
    {
        // Définir les en-têtes personnalisés à récupérer
        $headerEntete = str_replace(" ", "", ucwords($nameEntete));
        $plugin_headers = array($headerEntete => $nameEntete);

        // DEBUG:
        // error_log("Pluging file : " . $this->plugin_file);
        // Récupérer les données du plugin
        $plugin_data = get_file_data($this->plugin_file, $plugin_headers);
        // Récupérer la valeur de l'en-tête
        $text_domain_min = $plugin_data[$headerEntete];

        // Retourner la valeur de l'en-tête
        return !empty($text_domain_min) ? $text_domain_min : "";
    }

    /**
     * Définit une constante si elle n'est pas déjà définie.
     *
     * @param string $constant_name Le nom de la constante à définir.
     * @param mixed $value La valeur de la constante.
     * @throws Exception Si la constante est déjà définie.
     */
    public function DefineConstant($constant_name, $value)
    {
        $myConst = strtoupper($this->varEntete('Text Domain min') . "_" . $constant_name);
        if (!defined($myConst)) {
            define($myConst, $value);
        } else {
            throw new Exception("La constante $myConst est déjà définie.");
        }
    }

    /**
     * Récupère la valeur d'une constante.
     *
     * @param string $constant_name Le nom de la constante.
     * @return mixed La valeur de la constante.
     * @throws Exception Si la constante n'existe pas.
     */
    public function ValConstant($constant_name)
    {
        $constant_rename = strtoupper($this->varEntete('Text Domain min') . "_" . $constant_name);
        if (defined($constant_rename)) {
            return constant($constant_rename);
        } else {
            throw new Exception("La constante $constant_rename n'est pas définie.");
        }
    }

    /**
     * Inclut un fichier en fonction d'une constante.
     *
     * @param string $constant_name Le nom de la constante.
     * @param string $file_path Le chemin relatif à partir de la constante.
     * @throws Exception Si la constante n'existe pas ou si le fichier n'existe pas.
     */
    public function RequireFile($constant_name, $file_path)
    {
        $folder_path = $this->ValConstant($constant_name);
        $full_path = $folder_path . $file_path;

        if (file_exists($full_path)) {
            require_once $full_path;
        } else {
            throw new Exception("Le fichier $full_path n'existe pas.");
        }
    }

    /**
     * Définit les constantes de base à partir d'un tableau de paramètres.
     *
     * @param array $var_constants Tableau contenant les clés et valeurs des constantes.
     * @throws Exception Si une clé requise est manquante.
     */
    public function CallConstante($var_constants)
    {
        // Vérification de la présence des clés requises
        $required_keys = ['NAME', 'VERSION', 'DIR_PATH', 'INC_PATH', 'ADMIN_PATH', 'URL_PATH', 'ASSETS_PATH'];
        foreach ($required_keys as $key) {
            if (!isset($var_constants[$key])) {
                throw new Exception("La clé '$key' est manquante dans \$var_constants.");
            }
        }

        // Définition des constantes de base
        $constants_to_define = [
            'NAME' => $var_constants['NAME'],
            'VERSION' => $var_constants['VERSION'],
            'URI_DIR' => $var_constants['DIR_PATH'],
            'URI_INC' => $var_constants['DIR_PATH'] . $var_constants['INC_PATH'],
            'URI_ADMIN' => $var_constants['DIR_PATH'] . $var_constants['ADMIN_PATH'],
            'URL_DIR' => $var_constants['URL_PATH'],
            'URL_INC' => $var_constants['URL_PATH'] . $var_constants['INC_PATH'],
            'URL_JS' => $var_constants['URL_PATH'] . $var_constants['ASSETS_PATH'] . '/js',
            'URL_ADMIN' => $var_constants['URL_PATH'] . $var_constants['ADMIN_PATH'],
        ];

        // Boucle pour définir les constantes
        foreach ($constants_to_define as $constant_name => $value) {
            $this->defineConstant($constant_name, $value);
        }
    }
}
