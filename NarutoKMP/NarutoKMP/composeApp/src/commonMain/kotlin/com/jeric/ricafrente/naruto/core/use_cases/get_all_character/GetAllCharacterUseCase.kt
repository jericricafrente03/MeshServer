package com.jeric.ricafrente.naruto.core.use_cases.get_all_character

import androidx.paging.PagingData
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import kotlinx.coroutines.flow.Flow


class GetAllCharacterUseCase(
    private val repository: RemoteDataSource
) {
    operator fun invoke(): Flow<PagingData<CharacterModel>> {
        return repository.getAllHeroes()
    }

}