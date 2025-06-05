package com.jeric.naruto.domain.use_cases.get_all_tailbeast

import com.jeric.naruto.data.repository.RepositoryImpl
import com.jeric.naruto.domain.model.tail_beast.TailedBeastModel
import kotlinx.coroutines.flow.Flow

class GetAllTailBeastUseCase(
    private val repository: RepositoryImpl
) {
    operator fun invoke(): Flow<List<TailedBeastModel>> {
        return repository.getAllTailBeast()
    }
}