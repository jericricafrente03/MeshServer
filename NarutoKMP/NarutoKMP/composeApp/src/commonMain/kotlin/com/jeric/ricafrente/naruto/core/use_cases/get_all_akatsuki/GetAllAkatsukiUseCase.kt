package com.jeric.ricafrente.naruto.core.use_cases.get_all_akatsuki

import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.utils.DataState
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import kotlinx.coroutines.flow.Flow

class GetAllAkatsukiUseCase(
    private val repository: RemoteDataSource
) {
    operator fun invoke(): Flow<DataState<List<AkatsukiModel>>> {
        return repository.getAkatsuki()
    }
}