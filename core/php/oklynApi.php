<?php

class oklynApi
{
    private $_apiToken;
    protected const URL = 'https://api.oklyn.fr/public/v1';

    public function __construct($apiToken)
    {
        $this->_apiToken = $apiToken;
    }

    /**
     * Méthode: GET
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/pump
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * @throws Exception
     */
    public function getPompe(string $value){
        $request_http = new com_http(self::URL . '/device/my/pump');
        $request_http->setHeader([
            'Content-Type: application/json',
            'X-API-TOKEN: '. $this->_apiToken
        ]);

        $response = json_decode($request_http->exec(), true);

        return $response[$value];
    }

    /**
     * Méthode: PUT
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/pump
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * pump peut prendre comme valeur « on », « off », et « auto »
     * @throws Exception
     */
    public function putPompe(string $value): string
    {
        $data = [
            'pump' => $value
        ];
        $request_http = new com_http(self::URL . '/device/my/pump');
        $request_http->setHeader([
            'Content-Type: application/json',
            'X-API-TOKEN: '. $this->_apiToken
        ]);
        $request_http->setPut(json_encode($data));

        return $request_http->exec();
    }

    /**
     * Méthode: GET
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/data/{typeDeMesure}
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * {typeDeMesure} est à remplacer par le nom d’une mesure parmi air, water (température de l’eau), ph, orp (redox).
     * @throws Exception
     */
    public function getSonde(string $sonde, string $value) {
        $request_http = new com_http(self::URL . '/device/my/data/'.$sonde);
        $request_http->setHeader([
            'Content-Type: application/json',
            'X-API-TOKEN: '. $this->_apiToken
        ]);

        $response = json_decode($request_http->exec(), true);

        return $response[$value];
    }

    /**
     * Méthode: GET
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/aux
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * @throws Exception
     */
    public function getAux(string $aux, string $value){
        $request_http = new com_http(self::URL . '/device/my/'.$aux);
        $request_http->setHeader([
            'Content-Type: application/json',
            'X-API-TOKEN: '. $this->_apiToken
        ]);

        $response = json_decode($request_http->exec(), true);

        return $response[$value];
    }

    /**
     * Méthode: PUT
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/aux
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * pump peut prendre comme valeur « on », « off »
     * @throws Exception
     */
    public function putAux(string $aux, string $value): string
    {
        $data = [
            'aux' => $value
        ];
        $request_http = new com_http(self::URL . '/device/my/'.$aux);
        $request_http->setHeader([
            'Content-Type: application/json',
            'X-API-TOKEN: '. $this->_apiToken
        ]);
        $request_http->setPut(json_encode($data));

        return $request_http->exec();
    }
}