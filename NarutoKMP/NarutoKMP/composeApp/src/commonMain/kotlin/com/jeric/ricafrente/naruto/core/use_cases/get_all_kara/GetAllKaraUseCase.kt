package com.jeric.ricafrente.naruto.core.use_cases.get_all_kara

import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.utils.DataState
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSourceImplTest
import kotlinx.coroutines.flow.Flow

class GetAllKaraUseCase(
    private val repository: RemoteDataSourceImplTest
) {
    operator fun invoke(): Flow<DataState<List<KaraModel>>> {
        return repository.getKara()
    }
}