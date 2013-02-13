using System;
using System.ComponentModel;
using System.Configuration;
using Microsoft.Practices.EnterpriseLibrary.Common.Configuration;
using Microsoft.Practices.EnterpriseLibrary.Common.Configuration.ObjectBuilder;
using Microsoft.Practices.ObjectBuilder;
using Sante.EMR.SmartClient.Infrastructure.Data;
using Microsoft.Practices.EnterpriseLibrary.Caching.BackingStoreImplementations;
using Microsoft.Practices.EnterpriseLibrary.Caching.Configuration;

namespace Sante.EMR.SmartClient.Infrastructure.Data.Configuration
{
    /// <summary>
    /// Represents the configuration data for a <see cref="SmartClientCache"/>.
    /// </summary>	
    [Assembler(typeof(SmartClientCacheAssembler))]
    public class SmartClientCacheData : StorageEncryptionProviderData
    {
        /// <summary>
        /// Initializes a new instance of the <see cref="SmartClientCacheData"/> class.
        /// </summary>
        public SmartClientCacheData()
        {
        }

        /// <summary>
        /// Initializes a new instance of the <see cref="SmartClientCacheData"/> class.
        /// </summary>
        /// <param name="name">The name for the instance.</param>
        public SmartClientCacheData(string name)
            : base(name, typeof(SmartClientCache))
        {
        }

        // TODO: Add the configuration properties for SmartClientCacheData. The snippet for creating configuration properties would be useful.
    }

    /// <summary>
    /// This type supports the Enterprise Library infrastructure and is not intended to be used directly from your code.
    /// Represents the process to build a <see cref="SmartClientCache"/> described by a <see cref="SmartClientCacheData"/> configuration object.
    /// </summary>
    /// <remarks>This type is linked to the <see cref="SmartClientCacheData"/> type and it is used by the  Custom Factory
    /// to build the specific <see cref="IStorageEncryptionProvider"/> object represented by the configuration object.
    /// </remarks>
    public class SmartClientCacheAssembler : IAssembler<IStorageEncryptionProvider, StorageEncryptionProviderData>
    {
        /// <summary>
        /// Builds a <see cref="SmartClientCache"/> based on an instance of <see cref="StorageEncryptionProviderData "/>.
        /// </summary>
        /// <param name="context">The <see cref="IBuilderContext"/> that represents the current building process.</param>
        /// <param name="objectConfiguration">The configuration object that describes the object to build. Must be an instance of <see cref="StorageEncryptionProviderData "/>.</param>
        /// <param name="configurationSource">The source for configuration objects.</param>
        /// <param name="reflectionCache">The cache to use retrieving reflection information.</param>
        /// <returns>A fully initialized instance of <see cref="IStorageEncryptionProvider"/>.</returns>
        public IStorageEncryptionProvider Assemble(IBuilderContext context, StorageEncryptionProviderData objectConfiguration, IConfigurationSource configurationSource, ConfigurationReflectionCache reflectionCache)
        {

            SmartClientCacheData castObjectConfiguration
                = (SmartClientCacheData)objectConfiguration;

            // TODO: Decide whether the SmartClientCache constructor with discrete arguments is neccesary.
            SmartClientCache createdObject
                = new SmartClientCache(castObjectConfiguration);

            return createdObject;
        }

        #region IAssembler<IStorageEncryptionProvider,StorageEncryptionProviderData> Members

        public IStorageEncryptionProvider Assemble(Microsoft.Practices.ObjectBuilder2.IBuilderContext context, StorageEncryptionProviderData objectConfiguration, IConfigurationSource configurationSource, ConfigurationReflectionCache reflectionCache)
        {
            SmartClientCacheData castObjectConfiguration
                = (SmartClientCacheData)objectConfiguration;

            // TODO: Decide whether the SmartClientCache constructor with discrete arguments is neccesary.
            SmartClientCache createdObject
                = new SmartClientCache(castObjectConfiguration);

            return createdObject;
        }

        #endregion
    }
}
