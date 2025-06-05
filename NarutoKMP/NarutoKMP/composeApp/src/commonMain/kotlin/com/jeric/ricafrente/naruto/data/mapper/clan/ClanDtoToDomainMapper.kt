package com.jeric.ricafrente.naruto.data.mapper.clan

import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.network.model.clan.ClanDto


fun ClanDto.toDomainModel(): ClanModel {
    return ClanModel(
        id = this.id.toString(),
        name = this.name,
        characters = this.characters
    )
}

fun List<ClanDto>.toClanModels(): List<ClanModel> {
    return this.map { it.toDomainModel() }
}










