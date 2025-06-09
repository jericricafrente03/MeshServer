package com.jeric.ricafrente.naruto.core.database.dao

import app.cash.paging.PagingSource
import com.jeric.ricafrente.naruto.core.database.NarutoKMP
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.network.client.NarutoClient
import com.jeric.ricafrente.naruto.core.network.helper.NarutoApi
import com.jeric.ricafrente.naruto.core.utils.toJoinedString
import com.jeric.ricafrente.naruto.data.paging_source.CharacterPagingSource
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.IO
import kotlinx.coroutines.withContext

class NarutoCharacterDao(
    private val narutoDatabase: NarutoKMP,
    private val client: NarutoApi
) {
    private val query get() = narutoDatabase.narutoKMPQueries

    fun getAllCharacters(pageSize: Int): PagingSource<Int, CharacterModel> {
        return CharacterPagingSource(pageSize = pageSize, client = client)
    }

    suspend fun getCharacterById(id: String) = withContext(Dispatchers.IO) {
        query.selectCharacterById(id).executeAsOneOrNull()
    }

    suspend fun insertCharacter(character: List<CharacterModel>) = withContext(Dispatchers.IO) { //Todo
        character.forEach { result ->
            query.insertCharacter(
                id = result.id,
                name = result.name,
                images = result.images.toJoinedString(),
                jutsu = result.jutsu.toJoinedString(),
                natureType = result.natureType.toJoinedString(),
                tools = result.tools.toJoinedString(),
                father = result.father,
                mother = result.mother,
                brother = result.brother,
                daughter = result.daughter,
                wife = result.wife,
                birthdate = result.birthdate,
                bloodType = result.bloodType,
                sex = result.sex
            )
        }
    }

    suspend fun deleteCharacterById(id: String) = withContext(Dispatchers.IO) {
        query.deleteCharacterById(id)
    }

    suspend fun updateCharacter(character: CharacterModel) = withContext(Dispatchers.IO) {
        query.insertCharacter(
            id = character.id,
            name = character.name,
            images = character.images.toJoinedString(),
            jutsu = character.jutsu.toJoinedString(),
            natureType = character.natureType.toJoinedString(),
            tools = character.tools.toJoinedString(),
            father = character.father,
            mother = character.mother,
            brother = character.brother,
            daughter = character.daughter,
            wife = character.wife,
            birthdate = character.birthdate,
            bloodType = character.bloodType,
            sex = character.sex
        )
    }

    suspend fun deleteAllHeroes() = withContext(Dispatchers.IO) {
        query.deleteAllHeroes()
    }

}