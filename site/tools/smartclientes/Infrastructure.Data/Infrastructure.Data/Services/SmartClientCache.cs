using System;
using System.Collections.Specialized;
using System.Diagnostics;
using System.Collections.Generic;
using Microsoft.Practices.EnterpriseLibrary.Common.Configuration;
using Microsoft.Practices.EnterpriseLibrary.Common.Configuration.ObjectBuilder;
using Microsoft.Practices.EnterpriseLibrary.Common.Instrumentation;
using Microsoft.Practices.EnterpriseLibrary.Caching;
using Sante.EMR.SmartClient.Infrastructure.Data.Configuration;
using Microsoft.Practices.EnterpriseLibrary.Caching.BackingStoreImplementations;
using Sante.EMR.SmartClient.Infrastructure.Data.Services;
using System.Data;
using Sante.EMR.SmartClient.Infrastructure.Data.Properties;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;

namespace Sante.EMR.SmartClient.Infrastructure.Data
{
    /// <summary>
    /// TODO: Add SmartClientCache comment.
    /// </summary>
    [ConfigurationElementType(typeof(SmartClientCacheData))]
    public class SmartClientCache : IStorageEncryptionProvider, ICacheHandler
    {
        //private CacheManager _primitivesCache;
        private DataWebService.DataService _dataService;
       
        /// <summary>
        /// <para>Initializes a new instance of the <see cref="SmartClientCache"/>.</para>
        /// </summary>
        /// <param name="configuration">The configuration object used to set the runtime values</param>
        public SmartClientCache(SmartClientCacheData configuration)
        {
            // TODO: Use the SmartClientCacheData object to set runtime values for the SmartClientCache.
            //_primitivesCache = CacheFactory.GetCacheManager();
            _dataService = new DataWebService.DataService();
           // _localdb = new SanteDataSet();
            
        }


        // TODO: Decide whether a constructor with discrete arguments is necessary for the SmartClientCache.
        //public SmartClientCache(object param1, object param2)
        //{
        //    this.var1 = param1;
        //    this.var2 = param2;
        //}


        #region IStorageEncryptionProvider Members

        /// <summary>
        /// Decrypt backing store data.
        /// </summary>
        /// <param name="ciphertext">Encrypted bytes.</param>
        /// <returns>Decrypted bytes.</returns>
        public byte[] Decrypt(byte[] ciphertext)
        {
            throw new Exception("The method or operation is not implemented.");
        }

        /// <summary>
        /// Encrypt backing store data.
        /// </summary>
        /// <param name="plaintext">Clear bytes.</param>
        /// <returns>Encrypted bytes.</returns>
        public byte[] Encrypt(byte[] plaintext)
        {
            throw new Exception("The method or operation is not implemented.");
        }

        #endregion


      

        #region ICacheHandler Members

        //public object GetData(string key)
        //{
        //    //return _primitivesCache.GetData(key);
        //}

        #endregion

        #region ICacheHandler Members


        public void FlushCache()
        {
            //_primitivesCache.Flush();    
        }

        public bool AddData(string key, object value)
        {
            //_primitivesCache.Add(key, value);

            return true;
        }

        public bool SyncCache()
        {
            FlushCache();
            
            //// make a call to the server to retrieve data
            ////AddData(DataAccessService.OPS_KEY, _dataService.RetrieveOPSCases());
            ////_dataService.
            //if (_dataService.HelloWorld() != "")
            //    return true;

            return false;
        }

        #endregion

        #region ICacheHandler Members


       

        #endregion

        #region ICacheHandler Members

        public object GetData(string key)
        {
            throw new Exception("The method or operation is not implemented.");
        }

        #endregion
    }
}
