package com.jeric.naruto.di

import android.content.Context
import com.jeric.naruto.data.repository.PreferencesManagerImpl
import com.jeric.naruto.data.repository.RepositoryImpl
import com.jeric.naruto.domain.repository.PreferenceManager
import com.jeric.naruto.domain.use_cases.UseCases
import com.jeric.naruto.domain.use_cases.get_all_heroes.GetAllCharacterUseCase
import com.jeric.naruto.domain.use_cases.get_all_tailbeast.GetAllTailBeastUseCase
import dagger.Module
import dagger.Provides
import dagger.hilt.InstallIn
import dagger.hilt.android.qualifiers.ApplicationContext
import dagger.hilt.components.SingletonComponent
import javax.inject.Singleton

@Module
@InstallIn(SingletonComponent::class)
object PreferencesMngr {

    @Provides
    @Singleton
    fun preferencesManager(
        @ApplicationContext context: Context
    ): PreferenceManager {
        return PreferencesManagerImpl(context = context)
    }

    @Provides
    @Singleton
    fun provideUseCases(repository: RepositoryImpl): UseCases {
        return UseCases(
            getAllCharacterUseCase = GetAllCharacterUseCase(repository),
//            getAllTailBeastUseCase = GetAllTailBeastUseCase(repository)
        )
    }
}