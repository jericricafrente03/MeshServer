package com.jeric.ricafrente.naruto.data.mapper.akatsuki

import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.network.model.akatsuki.AkatsukiDto


fun AkatsukiDto.toDomainModel(): AkatsukiModel {
    return AkatsukiModel(
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

fun List<AkatsukiDto>.toAkatsukiModels(): List<AkatsukiModel> {
    return this.map { it.toDomainModel() }
}










