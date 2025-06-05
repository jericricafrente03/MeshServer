package com.jeric.ricafrente.naruto.core.use_cases.get_all_kara

import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.utils.DataState
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import kotlinx.coroutines.flow.Flow

class GetAllKaraUseCase(
    private val repository: RemoteDataSource
) {
    operator fun invoke(): Flow<DataState<List<KaraModel>>> {
        return repository.getAllKara()
    }
}