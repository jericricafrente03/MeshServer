package com.jeric.ricafrente.naruto.core.use_cases.get_all_akatsuki

import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.utils.DataState
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSourceImplTest
import kotlinx.coroutines.flow.Flow

class GetAllAkatsukiUseCase(
    private val repository: RemoteDataSourceImplTest
) {
    operator fun invoke(): Flow<DataState<List<AkatsukiModel>>> {
        return repository.getAkatsuki()
    }
}