package com.jeric.ricafrente.naruto.core.use_cases.search_character

import androidx.paging.PagingData
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSource
import com.jeric.ricafrente.naruto.data.repository.RemoteDataSourceImplTest
import kotlinx.coroutines.flow.Flow

class SearchHeroesUseCase(
    private val repository: RemoteDataSourceImplTest
) {
    operator fun invoke(query: String): Flow<PagingData<CharacterModel>> {
        return repository.searchHeroes(query = query)
    }
}