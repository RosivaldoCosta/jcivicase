using System;
using System.Collections.Generic;
using System.Text;
using System.Reflection;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;
using Sante.EMR.SmartClient.Infrastructure.Library.EntityTranslators;

namespace Sante.EMR.SmartClient.Infrastructure.Data.EntityTranslators
{
    public class TranslatorFactory<T,K> : EntityMapperTranslator<T, K> where T : new() where K : new()
    {

        public override bool CanTranslate(Type targetType, Type sourceType)
        {
            return base.CanTranslate(targetType, sourceType);
        }

        protected override K BusinessToService(IEntityTranslatorService service, T value)
        {
            K f = new K();
            foreach (PropertyInfo source in value.GetType().GetProperties())
            {
                PropertyInfo target = f.GetType().GetProperty(source.Name);
                if( target != null)
                {
                    object o = source.GetValue(value, null);
                    target.GetSetMethod().Invoke(f, new object[] { o });
                }
            
            }
        
            return f;

        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="service"></param>
        /// <param name="value"></param>
        /// <returns></returns>
        protected override T ServiceToBusiness(IEntityTranslatorService service, K value)
        {
            T form = new T();

            foreach (PropertyInfo p in form.GetType().GetProperties())
            {
                PropertyInfo source = value.GetType().GetProperty(p.Name);
                if (source != null)
                {
                    object val = source.GetValue(value, null);
                    p.GetSetMethod().Invoke(form, new object[] { val });

                    if (source.PropertyType == typeof(DateTime))
                    {
                        DateTime dt = (DateTime)val;
                        if (dt.Year == 1)
                            p.GetSetMethod().Invoke(form, new object[] { new DateTime(1900, 1, 1) });
                    }
                }
            }

            return form;
        }
    }
}
