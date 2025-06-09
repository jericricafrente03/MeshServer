package com.jeric.ricafrente.naruto.core.use_cases.get_all_character

import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSourceImplTest

class GetSelectedCharacterUseCase(
    private val repository: RemoteDataSourceImplTest
) {
    suspend operator fun invoke(heroId: Int): CharacterModel {
        return repository.getSelectedCharacter(clan = heroId)
    }
}