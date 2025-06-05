package com.jeric.ricafrente.naruto.core.network.helper

import com.jeric.ricafrente.naruto.core.network.error.NarutoError
import com.jeric.ricafrente.naruto.core.network.error.NarutoException
import io.ktor.client.call.body
import io.ktor.client.statement.HttpResponse
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.IO
import kotlinx.coroutines.withContext

suspend inline fun <reified T> handleErrors(
    crossinline response: suspend () -> HttpResponse
): T = withContext(Dispatchers.IO) {

    val result = try {
        response()
    } catch (e: kotlinx.io.IOException) {
        throw NarutoException(NarutoError.ServiceUnavailable)
    }

    when(result.status.value) {
        in 200..299 -> Unit
        in 400..499 -> throw NarutoException(NarutoError.ClientError)
        500 -> throw NarutoException(NarutoError.ServerError)
        else -> throw NarutoException(NarutoError.UnknownError)
    }

    return@withContext try {
        result.body()
    } catch(e: Exception) {
        throw NarutoException(NarutoError.ServerError)
    }

}