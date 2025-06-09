package com.jeric.ricafrente.naruto.data.paging_source

import androidx.paging.PagingSource
import androidx.paging.PagingState
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.network.helper.NarutoApi
import com.jeric.ricafrente.naruto.data.mapper.character.fromDtoToModel

class SearchCharacterPagingSource(
    private val narutoClient: NarutoApi,
    private val query: String
) : PagingSource<Int, CharacterModel>() {

    private val initialPageNo = 1

    override suspend fun load(params: LoadParams<Int>): LoadResult<Int, CharacterModel> {
        val currentPage = params.key ?: initialPageNo
        return try {
            val response = narutoClient.searchCharactersByName(
                name = query,
                page = currentPage,
                limit = 10
            )
            val characters = response.characters.fromDtoToModel()

            LoadResult.Page(
                data = characters,
                prevKey = if (currentPage == initialPageNo) null else currentPage - 1,
                nextKey = if (characters.isEmpty()) null else currentPage + 1
            )
        } catch (e: Exception) {
            LoadResult.Error(e)
        }
    }

    override fun getRefreshKey(state: PagingState<Int, CharacterModel>): Int? {
        return state.anchorPosition?.let { position ->
            state.closestPageToPosition(position)?.prevKey?.plus(1)
                ?: state.closestPageToPosition(position)?.nextKey?.minus(1)
        }
    }
}