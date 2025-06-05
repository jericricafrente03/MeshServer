package com.jeric.ricafrente.naruto.core.use_cases.get_all_tailbeast

import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource


class GetSelectedTailedBeastUseCase(
    private val repository: RemoteDataSource
) {
    suspend operator fun invoke(heroId: Int): TailedBeastModel {
        return repository.getSelectedTailedBeast(beast = heroId)
    }
}