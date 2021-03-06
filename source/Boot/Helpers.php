<?php

/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

/**
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo'] || (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
        return true;
    }

    return false;
}

/**
 * Facilita a verificação do empty nas visões
 * Aceita strings, objetos, numeros e pontos flutuantes
 *
 * @param mixed $value * Esse é o valor a ser verificado
 * @param string|null $left * Se aqui for passado 'self' ele retornara o $value após a verificação
 * @param mixed $right * aqui é o valor caso o valor ou o objeto seja vazio
 * @return mixed
 */
function isnt_empty($value, $left = "", $right = "")
{

    if ((mb_convert_case($left, MB_CASE_LOWER) === 'self') && !empty($value)) {
        $left = $value;
    }

    if (!is_object($value) && !is_object($left) && !is_array($value) && !is_array($left)) {
        if ((mb_convert_case($left, MB_CASE_LOWER) === '') && !empty($left)) {
            return $value;
        }
    }

    return (!empty($value) && !empty($left) ? $left : $right);
}

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(["-----", "----", "---", "--"], "-",
        str_replace(" ", "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
    return $slug;
}

/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    $studlyCase = str_replace(" ", "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );

    return $studlyCase;
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
}

/**
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * @param string $path
 * @return string
 */
function url(string $path = null): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
        if ($path) {
            return CONF_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST;
    }

    if ($path) {
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;
}

/**
 * @return string
 */
function url_back(): string
{
    return ($_SERVER['HTTP_REFERER'] ?? url());
}

/**
 * @param string $url
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }

    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
        $location = url($url);
        header("Location: {$location}");
        exit;
    }
}

/**
 * @param string $money
 * @return string
 */
function money_fmt_br(?float $money, bool $brl = false): string
{
    if ($brl) {
        $money = 'R$ ' . number_format($money, 2, ',', '.');
    } else {
        $money = number_format($money, 2, ',', '.');
    }
    return $money;
}

/**
 * @param float|null $money
 * @return string
 */
function money_fmt_app(?string $money)
{
    if (!strstr($money, ',')) {
        return (float)$money;
    }
    return (float)str_replace(',', '.', str_replace('.', '', $money));
}

/**
 * ##################
 * ###   ASSETS   ###
 * ##################
 */

/**
 * @param string|null $path
 * @return string
 */
function theme(string $path = null, string $theme = CONF_VIEW_THEME): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
        if ($path) {
            return CONF_URL_TEST . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }

        return CONF_URL_TEST . "/themes/{$theme}";
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE . "/themes/{$theme}";
}

/**
 * @param string $image
 * @param int $width
 * @param int|null $height
 * @return string
 */
function image(string $image, int $width, int $height = null): string
{
    return url() . "/" . (new \Source\Support\Thumb())->make($image, $width, $height);
}

/**
 * ################
 * ###   DATE   ###
 * ################
 */

/**
 * @param string $date
 * @param string $format
 * @return string
 */
function date_fmt(string $date = "now", string $format = "d/m/Y H\hi"): string
{
    return (new DateTime($date, (new DateTimeZone('America/Sao_Paulo'))))->format($format);
}

/**
 * @param string $date
 * @return string
 */
function date_fmt_br(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_BR);
}

/**
 * @param string $date
 * @return string
 */
function date_fmt_app(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_APP);
}


/**
 * RETORNA O DIA DA DATA
 * @param string $dateParam
 * @return string
 */
function weekDay(string $dateParam, bool $isNumber = false): string
{
    $weekDays = array(
        1 => "Segunda-Feira",
        2 => "Terça-Feira",
        3 => "Quarta-Feira",
        4 => "Quinta-Feira",
        5 => "Sexta-Feira",
        6 => "Sábado",
        0 => "Domingo"
    );

    $date = $dateParam;
    $date = str_replace('/', '-', $date);

    $today = getdate(strtotime($date));

    $weekDay = $today["wday"];
    if ($isNumber) {
        return $weekDay;
    } else {
        return $weekDays[$weekDay];
    }
}

/**
 * ####################
 * ###   PASSWORD   ###
 * ####################
 */

/**
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    if (!empty(password_get_info($password)['algo'])) {
        return $password;
    }

    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * ###################
 * ###   REQUEST   ###
 * ###################
 */

/**
 * @return string
 */
function csrf_input(): string
{
    $session = new \Source\Core\Session();
    $session->csrf();
    return "<input type='hidden' name='csrf' value='" . ($session->csrf_token ?? "") . "'/>";
}

/**
 * @param $request
 * @return bool
 */
function csrf_verify($request): bool
{
    $session = new \Source\Core\Session();
    if (empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token) {
        return false;
    }
    return true;
}

/**
 * @return null|string
 */
function flash(): ?string
{
    $session = new \Source\Core\Session();
    if ($flash = $session->flash()) {
        echo $flash;
    }
    return null;
}

function more_than_on_negative(array $values): bool
{
    foreach ($values as $value) {
        $len = mb_strlen($value);
        $errorNegative = 0;
        for ($i = 0; $i < $len; $i++) {
            if ($value[$i] === '-') {
                $errorNegative++;
            }
        }
        if ($errorNegative > 1) {
            $bools[] = true;
        } else {
            $bools[] = false;
        }
    }
    if (in_array(true, $bools)) {
        return false;
    } else {
        return true;
    }
}

function is_not_zero($value)
{
    return (
        !empty($value)
        && money_fmt_app($value) !== '0'
        && money_fmt_app($value) !== 0
        && money_fmt_app($value) !== '0.00'
        && money_fmt_app($value) !== 0.00
        && money_fmt_app($value) !== '00.0'
        && money_fmt_app($value) !== 00.00
        && money_fmt_app($value) !== '00.00'
        && money_fmt_app($value) !== 00.0
        && money_fmt_app($value) !== '00.0'
        && money_fmt_app($value) !== 00.0
        && money_fmt_app($value) !== '00.0'
    );
}