package com.jeric.ricafrente.naruto.core.use_cases.search_character

import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource

class GetSelectedHeroesUseCase(
    private val repository: RemoteDataSource
) {
    suspend operator fun invoke(id: Int): CharacterModel {
        return repository.getSelectedCharacter(id)
    }
}