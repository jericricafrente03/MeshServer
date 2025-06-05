package com.jeric.ricafrente.naruto.core.use_cases.get_all_tailbeast

import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.utils.DataState
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import kotlinx.coroutines.flow.Flow

class GetAllTailBeastUseCase(
    private val repository: RemoteDataSource
) {
    operator fun invoke(): Flow<DataState<List<TailedBeastModel>>> {
        return repository.getAllTailBeast()
    }
}