package com.jeric.ricafrente.naruto.data.mapper.akatsuki

import com.jeric.ricafrente.naruto.core.database.Akatsuki_table
import com.jeric.ricafrente.naruto.core.database.TailedBeastEntity
import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel
import com.jeric.ricafrente.naruto.core.utils.convertStringToList
import com.jeric.ricafrente.naruto.data.mapper.character.toDomainModel

fun Akatsuki_table.toDomainModel(): AkatsukiModel {
    return AkatsukiModel(
        id = this.id,
        name = this.name,
        images = this.images.convertStringToList(),
        jutsu = this.jutsu.convertStringToList(),
        natureType = this.natureType.convertStringToList(),
        tools = this.tools.convertStringToList(),
        father = this.father,
        mother = this.mother,
        brother = this.brother,
        daughter = this.daughter,
        wife = this.wife,
        birthdate = this.birthdate,
        bloodType = this.bloodType,
        sex = this.sex
    )
}


fun List<Akatsuki_table>.fromEntityToModel(): List<AkatsukiModel> {
    return this.map { it.toDomainModel() }
}
