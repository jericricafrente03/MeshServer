package com.jeric.ricafrente.naruto.core.use_cases.search_character

import androidx.paging.PagingData
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import kotlinx.coroutines.flow.Flow

class SearchHeroesUseCase(
    private val repository: RemoteDataSource
) {
    operator fun invoke(query: String): Flow<PagingData<CharacterModel>> {
        return repository.searchHeroes(query = query)
    }
}