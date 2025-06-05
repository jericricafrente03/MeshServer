package com.jeric.ricafrente.naruto.data.mapper.clan

import com.jeric.ricafrente.naruto.core.database.Akatsuki_table
import com.jeric.ricafrente.naruto.core.database.Clan_table
import com.jeric.ricafrente.naruto.core.database.TailedBeastEntity
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.utils.convertStringToList
import com.jeric.ricafrente.naruto.core.utils.toIntList
import com.jeric.ricafrente.naruto.data.mapper.character.toDomainModel

fun Clan_table.toDomainModel(): ClanModel {
    return ClanModel(
        characters = this.characters.toIntList(),
        id = this.id,
        name = this.name
    )
}


fun List<Clan_table>.fromEntityToModel(): List<ClanModel> {
    return this.map { it.toDomainModel() }
}
