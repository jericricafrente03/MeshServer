package com.jeric.naruto.domain.use_cases

import com.jeric.naruto.domain.use_cases.get_all_heroes.GetAllCharacterUseCase
import com.jeric.naruto.domain.use_cases.get_all_tailbeast.GetAllTailBeastUseCase

data class UseCases(
    val getAllCharacterUseCase: GetAllCharacterUseCase,
//    val getAllTailBeastUseCase: GetAllTailBeastUseCase
)