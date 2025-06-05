package com.jeric.naruto.data.mapper.character

import com.jeric.naruto.data.remote.dto.character.CharacterDto
import com.jeric.naruto.data.remote.dto.character.FamilyDto
import com.jeric.naruto.data.remote.dto.character.PersonalDto
import com.jeric.naruto.domain.model.character.CharacterModel
import com.jeric.naruto.domain.model.character.Family
import com.jeric.naruto.domain.model.character.Personal

fun CharacterDto.toDomainModel(): CharacterModel {
    return CharacterModel(
        id = this.id.toString(),
        name = this.name,
        images = this.images,
        family = this.family?.toFamilyModel(),
        jutsu = this.jutsu,
        natureType = this.natureType,
        personal = this.personal.toPersonalModel(),
        tools = this.tools,
    )
}


fun FamilyDto.toFamilyModel(): Family {
    return Family(father, mother, brother, daughter, wife)
}

fun PersonalDto.toPersonalModel(): Personal {
    return Personal(
        birthdate = this.birthdate,
        bloodType = this.bloodType,
        sex = this.sex,
    )
}



