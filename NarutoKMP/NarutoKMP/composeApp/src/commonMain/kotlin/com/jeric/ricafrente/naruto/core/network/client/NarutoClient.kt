package com.jeric.ricafrente.naruto.core.network.client

import com.jeric.ricafrente.naruto.core.network.helper.handleErrors
import com.jeric.ricafrente.naruto.core.network.model.akatsuki.AkatsukiResponse
import com.jeric.ricafrente.naruto.core.network.model.buruto.BorutoResponse
import com.jeric.ricafrente.naruto.core.network.model.character.CharacterDto
import com.jeric.ricafrente.naruto.core.network.model.character.CharactersResponse
import com.jeric.ricafrente.naruto.core.network.model.clan.ClanResponse
import com.jeric.ricafrente.naruto.core.network.model.tailbeast.TailBeastResponse
import com.jeric.ricafrente.naruto.core.utils.Constants
import io.ktor.client.HttpClient
import io.ktor.client.call.body
import io.ktor.client.request.get
import io.ktor.client.request.parameter

class NarutoClient(private val httpClient: HttpClient) {

    private suspend inline fun <reified T> get(endpoint: String, params: Map<String, Any?> = emptyMap()): T =
        handleErrors {
            httpClient.get("${Constants.BASE_URL}/$endpoint") {
                params.forEach { (key, value) -> parameter(key, value) }
            }.body()
        }

    suspend fun getAllCharacters(page: Int = 1, limit: Int = Constants.SIZE): CharactersResponse =
        get("characters", mapOf("page" to page, "limit" to limit))

    suspend fun searchCharactersByName(
        name: String,
        page: Int = 1,
        limit: Int = Constants.SIZE
    ): CharactersResponse = get("characters", mapOf("name" to name, "page" to page, "limit" to limit))

    suspend fun getCharacterById(id: String): CharacterDto =
        get("characters/$id")

    suspend fun getAllTailBeasts(): TailBeastResponse = get("tailed-beasts")

    suspend fun getClan(): ClanResponse = get("clans")

    suspend fun getAkatsuki(): AkatsukiResponse = get("akatsuki")

    suspend fun getBoruto(): BorutoResponse = get("kara")
}