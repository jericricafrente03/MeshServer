package com.jeric.ricafrente.naruto.core.network.error

enum class NarutoError {
    ServiceUnavailable,
    ClientError,
    ServerError,
    UnknownError
}

class NarutoException(error: NarutoError): Exception(
    "Something goes wrong: $error"
)