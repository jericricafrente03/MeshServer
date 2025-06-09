package com.jeric.ricafrente.naruto.data.paging_source

import app.cash.paging.PagingSource
import app.cash.paging.PagingState
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.network.client.NarutoClient
import com.jeric.ricafrente.naruto.core.network.helper.NarutoApi
import com.jeric.ricafrente.naruto.core.utils.toJoinedString

class CharacterPagingSource(
    private val pageSize: Int,
    private val client: NarutoApi
) : PagingSource<Int, CharacterModel>() {

    override suspend fun load(params: LoadParams<Int>): LoadResult<Int, CharacterModel> {
        return try {
            val page = params.key ?: 1
            val response = client.getAllCharacters(page = page, limit = pageSize)

            val results = response.characters.map { dto ->
                CharacterModel(
                    id = dto.id.toString(),
                    name = dto.name,
                    images = dto.images,
                    jutsu = dto.jutsu,
                    natureType = dto.natureType,
                    tools = dto.tools,
                    father = dto.family?.father,
                    mother = dto.family?.mother,
                    brother = dto.family?.brother,
                    daughter = dto.family?.daughter,
                    wife = dto.family?.wife,
                    birthdate = dto.personal?.birthdate,
                    bloodType = dto.personal?.bloodType,
                    sex = dto.personal?.sex
                )
            }

            LoadResult.Page(
                data = results,
                prevKey = if (page == 1) null else page - 1,
                nextKey = if (results.size < pageSize) null else page + 1
            )
        } catch (e: Exception) {
            LoadResult.Error(e)
        }
    }

    override fun getRefreshKey(state: PagingState<Int, CharacterModel>): Int? {
        return state.anchorPosition?.let { anchor ->
            state.closestPageToPosition(anchor)?.prevKey?.plus(1)
                ?: state.closestPageToPosition(anchor)?.nextKey?.minus(1)
        }
    }
}