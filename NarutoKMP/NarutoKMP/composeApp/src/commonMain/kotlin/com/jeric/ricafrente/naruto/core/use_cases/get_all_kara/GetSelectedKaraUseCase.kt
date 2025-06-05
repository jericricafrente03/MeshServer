package com.jeric.ricafrente.naruto.core.use_cases.get_all_kara

import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource

class GetSelectedKaraUseCase(
    private val repository: RemoteDataSource
) {
    suspend operator fun invoke(id: Int): KaraModel {
        return repository.getSelectedKara(kara = id)
    }
}