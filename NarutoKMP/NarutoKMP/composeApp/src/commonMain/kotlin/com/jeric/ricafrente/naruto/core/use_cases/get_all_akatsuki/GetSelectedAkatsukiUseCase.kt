package com.jeric.ricafrente.naruto.core.use_cases.get_all_akatsuki

import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource

class GetSelectedAkatsukiUseCase(
    private val repository: RemoteDataSource
) {
    suspend operator fun invoke(id: Int): AkatsukiModel {
        return repository.getSelectedAkatsuki(akatsukiModel = id)
    }
}