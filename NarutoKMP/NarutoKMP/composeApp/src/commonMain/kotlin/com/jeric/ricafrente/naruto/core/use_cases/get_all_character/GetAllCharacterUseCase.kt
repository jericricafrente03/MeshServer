package com.jeric.ricafrente.naruto.core.use_cases.get_all_character

import androidx.paging.PagingData
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSourceImplTest
import kotlinx.coroutines.flow.Flow


class GetAllCharacterUseCase(
    private val repository: RemoteDataSourceImplTest
) {
    operator fun invoke(): Flow<PagingData<CharacterModel>> {
        return repository.getAllCharacter()
    }

}