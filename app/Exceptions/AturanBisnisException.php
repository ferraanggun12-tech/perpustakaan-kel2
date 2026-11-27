<?php

namespace App\Exceptions;

use RuntimeException;

// Dilempar ketika sebuah aturan bisnis dilanggar (pesannya aman ditampilkan ke pengguna)
class AturanBisnisException extends RuntimeException
{
}
