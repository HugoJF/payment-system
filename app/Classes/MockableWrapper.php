<?php

namespace App\Classes;

use Exception;

class MockableWrapper
{
    public static $saving = false;
    public static $mocking = false;
    public static $responses = [];
    public static $folder = 'misc';
    public static $exceptionWhenMockIsNotFound = true;

    /**
     * Instructs the class to mock responses
     */
    public static function startMocking()
    {
        static::$mocking = true;
    }

    /**
     * Stops mocking responses
     */
    public static function stopMocking()
    {
        static::$mocking = false;
    }

    /**
     * Starts saving every response
     */
    public static function startSaving()
    {
        static::$saving = true;
    }

    /**
     * Stops saving responses
     */
    public static function stopSaving()
    {
        static::$saving = false;
    }

    /**
     * Mock method
     *
     * @param $request  - function name to be mocked
     * @param $fileName - filename containing mock information
     */
    public static function mockByFile($request, $fileName)
    {
        $content = self::readFromFile($fileName);

        static::$responses[ $request ] = $content;
    }

    /**
     * Reads content from mocking folder
     *
     * @param $name - filename
     *
     * @return object - JSON decoded content
     */
    protected static function readFromFile($name)
    {
        $path = self::getPathByName($name);
        $file = fopen($path, 'r');
        $content = fread($file, filesize($path));
        fclose($file);

        return json_decode($content, true);
    }

    /**
     * Gets a full path for the correct Mocking folder
     *
     * @param $name - file name to be transformed to path
     *
     * @return string - path
     */
    protected static function getPathByName($name)
    {
        $folder = static::$folder;

        return app_path("Mock/$folder/" . preg_replace('/[^A-Z0-9]/i', '-', $name));
    }

    /**
     * @param $name      - the name of the calling function
     * @param $arguments - arguments
     *
     * @return mixed - return value of the calling function
     *
     * @throws Exception - when the calling function could not be found
     */
    public static function __callStatic($name, $arguments)
    {
        // Check if call should be mocked
        if (static::$mocking) {
            $mock = static::getMockedResponse($name);
            if ($mock) {
                return $mock;
            }
        }

        // Build forward function names
        $method = "_$name";
        $function = "static::$method";

        // Attempts to forward the call
        if (!method_exists(static::class, $method)) {
            throw new Exception("Function $function does not exists");
        }

        try {
            $response = forward_static_call_array($function, $arguments);

            return static::saveResponse($name, $response);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Attempts to find a mocked response and throws error if mode allows
     *
     * @param $name - method name to be mocked
     *
     * @return mixed
     * @throws Exception
     */
    protected static function getMockedResponse($name)
    {
        // Check if we have a loaded mock for incoming function
        if (array_key_exists($name, static::$responses)) {
            return static::$responses[ $name ];
        }

        // Check if exception should be thrown
        if (static::$exceptionWhenMockIsNotFound) {
            throw new Exception("Trying to mock response $name but a mocked response was not set.");
        }

        // Return false since we could not mock the response
        return false;
    }

    /**
     * Saves response to file (if saving is activated)
     *
     * @param $name     - filename to be saved
     * @param $response - response contents
     *
     * @return mixed - response content
     */
    public static function saveResponse($name, $response)
    {
        if (static::$saving) {
            self::writeToFile($name . microtime(true), $response);
        }

        return $response;
    }

    /**
     * Write content to mocking folder
     *
     * @param $name    - filename
     * @param $content - content of mock
     */
    protected static function writeToFile($name, $content)
    {
        $file = fopen(self::getPathByName($name), 'w');
        fwrite($file, json_encode($content));
        fclose($file);
    }
}
