<?php


class Apioklyn
{
    private $_apiToken;


    public function __construct($apiToken)
    {
        $this->setApiToken($apiToken);
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken)
    {
        $this->_apiToken = $apiToken;
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->_apiToken;
    }

    /*
     * Méthode: GET
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/pump
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     */
    public function getPompe(string $value){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/pump",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $this->getApiToken(),
                "Content-Type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        return $response[$value];
    }

    /*
     * Méthode: PUT
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/pump
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * pump peut prendre comme valeur « on », « off », et « auto »
     */
    public function putPompe(string $value){
        $data = array("pump" => $value);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/pump",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $this->getApiToken(),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /*
     * Méthode: GET
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/data/{typeDeMesure}
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * {typeDeMesure} est à remplacer par le nom d’une mesure parmi air, water (température de l’eau), ph, orp (redox).
     */
    public function getSonde(string $sonde, string $value) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/data/".$sonde,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $this->getApiToken(),
                "Content-Type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        return $response[$value];
    }

    /*
     * Méthode: GET
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/aux
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     */
    public function getAux(string $value){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/aux",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $this->getApiToken(),
                "Content-Type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        return $response[$value];
    }

    /*
     * Méthode: PUT
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/aux
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * pump peut prendre comme valeur « on », « off »
     */
    public function putAux(string $value){
        $data = array("aux" => $value);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/aux",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $this->getApiToken(),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}