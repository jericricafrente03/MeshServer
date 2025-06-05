package com.jeric.naruto.domain.use_cases.get_all_heroes

import androidx.paging.PagingData
import com.jeric.naruto.data.repository.RepositoryImpl
import com.jeric.naruto.domain.model.character.CharacterModel
import kotlinx.coroutines.flow.Flow

class GetAllCharacterUseCase(
    private val repository: RepositoryImpl
) {
    operator fun invoke(): Flow<PagingData<CharacterModel>> {
        return repository.getAllCharacter()
    }
}