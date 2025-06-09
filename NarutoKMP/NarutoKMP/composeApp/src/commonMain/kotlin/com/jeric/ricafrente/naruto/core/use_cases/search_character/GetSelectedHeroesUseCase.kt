package com.jeric.ricafrente.naruto.core.use_cases.search_character

import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSourceImplTest

class GetSelectedHeroesUseCase(
    private val repository: RemoteDataSourceImplTest
) {
    suspend operator fun invoke(id: Int): CharacterModel {
        return repository.getSelectedCharacter(id)
    }
}