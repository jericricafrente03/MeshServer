package com.jeric.ricafrente.naruto.data.mapper.character

import com.jeric.ricafrente.naruto.core.database.TailedBeastEntity
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.network.model.character.CharacterDto
import com.jeric.ricafrente.naruto.core.utils.convertStringToList
import com.jeric.ricafrente.naruto.data.mapper.tailedbeast.toDomainModel


fun CharacterDto.toDomainModel(): CharacterModel {
    return CharacterModel(
        id = this.id.toString(),
        name = this.name,
        images = this.images,
        jutsu = this.jutsu,
        natureType = this.natureType,
        tools = this.tools,
        father = this.family?.father,
        mother = this.family?.mother,
        brother = this.family?.brother,
        daughter = this.family?.daughter,
        wife = this.family?.wife,
        birthdate = this.personal?.birthdate,
        bloodType = this.personal?.bloodType,
        sex = this.personal?.sex
    )
}


fun List<CharacterDto>.fromDtoToModel(): List<CharacterModel> {
    return this.map { it.toDomainModel() }
}










