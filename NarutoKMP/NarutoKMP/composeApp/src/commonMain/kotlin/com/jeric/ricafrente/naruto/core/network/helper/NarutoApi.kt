package com.jeric.ricafrente.naruto.core.network.helper

import com.jeric.ricafrente.naruto.core.network.model.akatsuki.AkatsukiResponse
import com.jeric.ricafrente.naruto.core.network.model.buruto.BorutoResponse
import com.jeric.ricafrente.naruto.core.network.model.character.CharacterDto
import com.jeric.ricafrente.naruto.core.network.model.character.CharactersResponse
import com.jeric.ricafrente.naruto.core.network.model.clan.ClanResponse
import com.jeric.ricafrente.naruto.core.network.model.tailbeast.TailBeastResponse
import com.jeric.ricafrente.naruto.core.utils.Constants

interface NarutoApi {
    suspend fun getAllCharacters(page: Int = 1, limit: Int = Constants.SIZE): CharactersResponse
    suspend fun searchCharactersByName(name: String, page: Int = 1, limit: Int = Constants.SIZE): CharactersResponse
    suspend fun getCharacterById(id: String): CharacterDto
    suspend fun getAllTailBeasts(): TailBeastResponse
    suspend fun getClan(): ClanResponse
    suspend fun getAkatsuki(): AkatsukiResponse
    suspend fun getBoruto(): BorutoResponse
}