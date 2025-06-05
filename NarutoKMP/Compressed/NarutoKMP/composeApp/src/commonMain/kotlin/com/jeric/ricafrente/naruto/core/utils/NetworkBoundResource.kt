package com.jeric.ricafrente.naruto.core.utils

import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.IO
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.emitAll
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.flow.flow
import kotlinx.coroutines.flow.flowOn
import kotlinx.coroutines.flow.map

inline fun <ResultType, RequestType> networkBoundResource(
    crossinline query: () -> Flow<ResultType>,
    crossinline fetch: suspend () -> RequestType,
    crossinline saveFetchResult: suspend (RequestType) -> Unit,
    crossinline shouldFetch: (ResultType) -> Boolean = { true }
) = flow {
    val data = query().first()

    val flow = if (shouldFetch(data)) {
        emit(DataState.Loading(data))

        try {
            saveFetchResult(fetch())
            query().map { DataState.Success(it) }
        } catch (throwable: Throwable) {
            query().map { DataState.Error(throwable.message ?: "Error", it) }
        }
    } else {
        query().map { DataState.Success(it) }
    }

    emitAll(flow)
}

